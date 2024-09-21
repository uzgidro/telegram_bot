<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected int $chatId;

    /**
     * @param array $update
     * @return void
     */
    public function parseUpdate(array $update): void
    {
        $model = new UpdateTG($update);
        $this->chatId = $model->message->chat->id;

        $destination = $this->onCommand($model);
        $this->onDestination($destination);
        dd(Users::all());
    }

    /**
     * @param UpdateTG $model
     * @return string
     */
    private function onCommand(UpdateTG $model): string
    {
        if ($model->message->entities->type == 'bot_command') {
            return $this->onStart($model);
        }
        return Destinations::undefined;
    }

    /**
     * @param string $destination
     * @return void
     */
    private function onDestination(string $destination): void
    {
        if ($destination == Destinations::languageSelect) {
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
     * @return string
     */
    private function onStart(UpdateTG $model): string
    {
        if ($model->message->text == '/start') {
            if (DB::table('users')->where('chat_id', $model->message->chat->id)->doesntExist()) {
                DB::table('users')->insert([
                    'chat_id' => $model->message->chat->id,
                    'first_name' => $model->message->from->firstName,
                    'last_name' => $model->message->from->lastName,
                    'username' => $model->message->from->username,
                    'destination' => Destinations::languageSelect,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return Destinations::languageSelect;
            }
        }
        return Destinations::undefined;
    }

}
