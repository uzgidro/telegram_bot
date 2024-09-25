<?php

namespace App\Http\Controllers;

use App\Models\CallbackData;
use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class CallbackController extends DestinationController
{
    /**
     * @param UpdateTG $update
     * @return void
     */
    public function index(UpdateTG $update): void
    {
//        // Отправляем ответ на callback_query (чтобы бот не висел)
//        Http::post("https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/answerCallbackQuery", [
//            'callback_query_id' => $model->callbackQuery->id,
//        ]);
//
//        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/deleteMessage', [
//            'chat_id' => $model->callbackQuery->message->chat->id,
//            'message_id' => $model->callbackQuery->message->id,
//        ]);
        if ($update->callbackQuery->data == CallbackData::HOME_LANGUAGE) {
            DB::table('users')
                ->where('chat_id', $update->callbackQuery->message->chat->id)
                ->update(['destination' => Destinations::LANGUAGE]);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_RU) {
            DB::table('users')->where('chat_id', $update->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::RU,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_UZ) {
            DB::table('users')->where('chat_id', $update->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::UZ,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_EN) {
            DB::table('users')->where('chat_id', $update->callbackQuery->message->chat->id)
                ->update([
                    'language' => Languages::EN,
                    'destination' => Destinations::HOME
                ]);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_CANCEL) {
            DB::table('users')->where('chat_id', $update->callbackQuery->message->chat->id)
                ->update([
                    'destination' => Destinations::HOME
                ]);
        }
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $update->callbackQuery->message->chat->id)->first()), $update);
    }
}
