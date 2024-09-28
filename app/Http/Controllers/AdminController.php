<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\Languages;
use App\Models\MessageType;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminController
{
    private HttpService $httpService;

    /**
     * @param HttpService $httpService
     */
    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }


    /**
     * @param Users $user
     * @param UpdateTG|null $update
     * @return void
     */
    public function anticor(Users $user, ?UpdateTG $update): void
    {
        $callbackData = $update->callbackQuery->data;
        $limit = 5;
        $text = '';
        switch ($user->language) {
            case Languages::RU:
            {
                $cancel = '🔙 На главную';
                break;
            }
            case Languages::UZ:
            {
                $cancel = '🔙 Bosh sahifaga';
                break;
            }
            default:
            {
                $cancel = '🔙 Home';
                break;
            }
        }
        if ($callbackData == CallbackData::INCOME_ANTICOR) {
            $collection = DB::table('messages')->where('type', MessageType::ANTICOR)->limit($limit)->get();
            $count = DB::table('messages')->where('type', MessageType::ANTICOR)->count();
            foreach ($collection as $item) {
                $formattedDate = date('H:i d.m.Y', strtotime($item->created_at));
                $text .= "*ID: " . $item->id . "*\n" . $item->text . "\n" . $formattedDate . "\n\n\n";
            }
            if (isset($update->callbackQuery->message->chat->id)) {
                $this->httpService->reactToCallback($update);
            }
            if ($text == '') {
                switch ($user->language) {
                    case Languages::RU:
                    {
                        $text = 'Список пуст';
                        break;
                    }
                    case Languages::UZ:
                    {
                        $text = 'Ro\'yxat bo\'sh';
                        break;
                    }
                    default:
                    {
                        $text = 'List is empty';
                        break;
                    }
                }
//                dd($text);
                Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
                    'chat_id' => $user->chat_id,
                    'text' => $text,
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['text' => $cancel, 'callback_data' => CallbackData::CANCEL]],
                        ],
                    ]),
                ]);
            } else {
                Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
                    'chat_id' => $user->chat_id,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            $count > $limit ? [
//                            ['text' => '⏪', 'callback_data' => CallbackData::ANTICOR_FIRST],
//                            ['text' => '◀️', 'callback_data' => CallbackData::ANTICOR_PREVIOUS],
                                ['text' => '1/' . ceil($count / $limit), 'callback_data' => CallbackData::BLANK],
                                ['text' => '▶️', 'callback_data' => CallbackData::ANTICOR_PAGING_PREFIX . '2'],
                                ['text' => '⏩', 'callback_data' => CallbackData::ANTICOR_PAGING_LAST],
                            ] : [],
                            [['text' => $cancel, 'callback_data' => CallbackData::CANCEL]],
                        ]
                    ]),
                ]);
            }
        } elseif (str_starts_with($callbackData, CallbackData::ANTICOR_PAGING_PREFIX)) {
            if ($callbackData == CallbackData::ANTICOR_PAGING_FIRST) {
                $collection = DB::table('messages')->where('type', MessageType::ANTICOR)->limit($limit)->get();
            } elseif ($callbackData == CallbackData::ANTICOR_PAGING_LAST) {

            } else {
                $page = intval(str_replace(CallbackData::ANTICOR_PAGING_PREFIX, '', $callbackData));
                $offset = ($page - 1) * 5;
                $collection = DB::table('messages')->where('type', MessageType::ANTICOR)->limit($limit)->offset($offset)->get();
                $count = DB::table('messages')->where('type', MessageType::ANTICOR)->count();
                foreach ($collection as $item) {
                    $formattedDate = date('H:i d.m.Y', strtotime($item->created_at));
                    $text .= "*ID: " . $item->id . "*\n" . $item->text . "\n" . $formattedDate . "\n\n\n";
                }
                if (isset($update->callbackQuery->message->chat->id)) {
                    $this->httpService->reactToCallback($update);
                }
                Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
                    'chat_id' => $user->chat_id,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            array_values(array_filter([
                                // Если страница не первая, добавляем кнопки "⏪" и "◀️"
                                $page != 1 ? ['text' => '⏪', 'callback_data' => CallbackData::ANTICOR_PAGING_FIRST] : null,
                                $page != 1 ? ['text' => '◀️', 'callback_data' => CallbackData::ANTICOR_PAGING_PREFIX . ($page - 1)] : null,

                                // Всегда отображаем кнопку с текущей страницей
                                ['text' => $page . '/' . ceil($count / $limit), 'callback_data' => CallbackData::BLANK],

                                // Если страница не последняя, добавляем кнопки "▶️" и "⏩"
                                $page < ceil($count / $limit) ? ['text' => '▶️', 'callback_data' => CallbackData::ANTICOR_PAGING_PREFIX . ($page + 1)] : null,
                                $page < ceil($count / $limit) ? ['text' => '⏩', 'callback_data' => CallbackData::ANTICOR_PAGING_LAST] : null,
                            ])),
                            // Добавляем кнопку отмены
                            [['text' => $cancel, 'callback_data' => CallbackData::CANCEL]],
                        ]
                    ]),
                ]);
            }
        }
    }
}
