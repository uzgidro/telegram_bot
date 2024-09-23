<?php

namespace App\Http\Controllers;

use App\Models\CallbackData;
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
    public LanguageController $languageController;

    /**
     * @param HomeController $homeController
     */
    public function __construct(HomeController $homeController, LanguageController $languageController)
    {
        $this->homeController = $homeController;
        $this->languageController = $languageController;
    }


    /**
     * @param array $update
     * @return void
     */
    public function parseUpdate(array $update): void
    {
        $model = new UpdateTG($update);

        if (isset($model->message->entities->type) && $model->message->entities->type == 'bot_command') {
            $this->onCommand($model);
        }
        if (isset($model->callbackQuery->data)) {
            $this->onCallback($model);
        }
    }

    /**
     * @param UpdateTG $model
     * @return void
     */
    private function onCommand(UpdateTG $model): void
    {
        if ($model->message->text == '/start') {
            $this->onStartCommand($model);
        }
        if ($model->message->text == '/home') {
            $this->onHomeCommand($model);
        }
    }

    private function onCallback(UpdateTG $model): void
    {
        // Отправляем ответ на callback_query (чтобы бот не висел)
        Http::post("https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/answerCallbackQuery", [
            'callback_query_id' => $model->callbackQuery->id,
        ]);

        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/deleteMessage', [
            'chat_id' => $model->callbackQuery->message->chat->id,
            'message_id' => $model->callbackQuery->message->id,
        ]);
        if ($model->callbackQuery->data == CallbackData::HOME_LANGUAGE) {
            DB::table('users')
                ->where('chat_id', $model->callbackQuery->message->chat->id)
                ->update(['destination' => Destinations::LANGUAGE]);
        }
        if ($model->callbackQuery->data == CallbackData::LANGUAGE_RU) {
            DB::table('users')->where('chat_id', $model->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::RU,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($model->callbackQuery->data == CallbackData::LANGUAGE_UZ) {
            DB::table('users')->where('chat_id', $model->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::UZ,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($model->callbackQuery->data == CallbackData::LANGUAGE_EN) {
            DB::table('users')->where('chat_id', $model->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::EN,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($model->callbackQuery->data == CallbackData::LANGUAGE_CANCEL) {
            DB::table('users')->where('chat_id', $model->callbackQuery->message->chat->id)
                ->update([
                    'destination' => Destinations::HOME
                ]);
        }
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $model->callbackQuery->message->chat->id)->first()));
    }

    /**
     * @param UpdateTG $model
     * @return void
     */
    private function onStartCommand(UpdateTG $model): void
    {
        // create user if not exists
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
        }

        // go to home page
        DB::table('users')
            ->where('chat_id', $model->message->chat->id)
            ->where('destination', '!=', Destinations::HOME)
            ->update(['destination' => Destinations::HOME]);
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $model->message->chat->id)->first()));
    }

    /**
     * @param UpdateTG $model
     * @return void
     */
    private function onHomeCommand(UpdateTG $model): void
    {
        // go to home page
        DB::table('users')
            ->where('chat_id', $model->message->chat->id)
            ->where('destination', '!=', Destinations::HOME)
            ->update(['destination' => Destinations::HOME]);
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $model->message->chat->id)->first()));
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
            $this->languageController->index($user);
//            Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
//                'chat_id' => $this->chatId,
//                'text' => 'Выбор языка',
//                'reply_markup' => json_encode([
//                    'inline_keyboard' => [
//                        [
//                            ['text' => 'Русский', 'callback_data' => 'lang_ru'],
//                            ['text' => 'Ўзбек', 'callback_data' => 'lang_uz'],
//                            ['text' => 'English', 'callback_data' => 'lang_en'],
//                        ]
//                    ]
//                ]),
//            ]);
        }
    }

}
