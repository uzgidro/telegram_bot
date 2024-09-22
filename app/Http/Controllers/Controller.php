<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected int $chatId;
    public HomeController $homeController;

    /**
     * @param HomeController $homeController
     */
    public function __construct(HomeController $homeController)
    {
        $this->homeController = $homeController;
    }


    /**
     * @param array $update
     * @return void
     */
    public function parseUpdate(array $update): void
    {
        $model = new UpdateTG($update);
        $this->chatId = $model->message->chat->id;

        $user = $this->onCommand($model);
        if ($user !== null) {
            $this->onDestination($user);
            dd(Users::all());
        }
    }

    /**
     * @param UpdateTG $model
     * @return mixed|Users
     */
    private function onCommand(UpdateTG $model): ?Users
    {
        if ($model->message->entities->type == 'bot_command') {
            return $this->onStart($model);
        }
        return null;
    }

    /**
     * @param Users $user
     * @return void
     */
    private function onDestination(Users $user): void
    {
        if ($user->destination == Destinations::HOME) {
            $this->homeController->index($user);
        }
        if ($user->destination == Destinations::LANGUAGE) {
            Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
                'chat_id' => $this->chatId,
                'text' => 'Выбор языка',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => 'Русский', 'callback_data' => 'lang_ru'],
                            ['text' => 'Ўзбек', 'callback_data' => 'lang_uz'],
                            ['text' => 'English', 'callback_data' => 'lang_en'],
                        ]
                    ]
                ]),
            ]);
        }
    }


    /**
     * @param UpdateTG $model
     * @return mixed|Users
     */
    private function onStart(UpdateTG $model): ?Users
    {
        if ($model->message->text == '/start') {
            if (DB::table('users')->where('chat_id', $model->message->chat->id)->doesntExist()) {
                if ($model->message->from->languageCode !== Languages::RU
                    && $model->message->from->languageCode !== Languages::UZ
                    && $model->message->from->languageCode !== Languages::EN) {
                    $language = Languages::EN;
                } else {
                    $language = $model->message->from->languageCode;
                }
                DB::table('users')->insert([
                    'chat_id' => $model->message->chat->id,
                    'first_name' => $model->message->from->firstName,
                    'last_name' => $model->message->from->lastName,
                    'username' => $model->message->from->username,
                    'destination' => Destinations::HOME,
                    'language' => $language,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return Users::createFromData(DB::table('users')->where('chat_id', $model->message->chat->id)->first());
            }
        }
        return null;
    }

}
