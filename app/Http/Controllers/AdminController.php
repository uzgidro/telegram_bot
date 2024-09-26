<?php

namespace App\Http\Controllers;

use App\Models\CallbackData;
use App\Models\MessageType;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminController
{
    /**
     * @param Users $user
     * @param UpdateTG|null $update
     * @return void
     */
    public function anticor(Users $user, ?UpdateTG $update): void
    {
        $limit = 5;
        if ($update->callbackQuery->data == CallbackData::INCOME_ANTICOR) {
            $collection = DB::table('messages')->where('type', MessageType::ANTICOR)->limit($limit)->get();
            $count = DB::table('messages')->where('type', MessageType::ANTICOR)->count();
            $text = '';
            foreach ($collection as $item) {
                $formattedDate = date('H:i d.m.Y', strtotime($item->created_at));
                $text .= "*ID: " . $item->id . "*\n" . $item->text . "\n".$formattedDate."\n\n\n";
            }
            Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
                'chat_id' => $user->chat_id,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        $count > $limit ? [
//                            ['text' => '⏪', 'callback_data' => CallbackData::ANTICOR_FIRST],
//                            ['text' => '◀️', 'callback_data' => CallbackData::ANTICOR_PREVIOUS],
                            ['text' => '1/'.ceil($count/$limit), 'callback_data' => CallbackData::BLANK],
                            ['text' => '▶️', 'callback_data' => CallbackData::ANTICOR_NEXT],
                            ['text' => '⏩', 'callback_data' => CallbackData::ANTICOR_LAST],
                        ] : [],
                    ]
                ]),
            ]);
        }
    }
}
