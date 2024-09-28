<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\InlineButton;
use App\Models\Languages;
use App\Models\MessageType;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

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
        if (str_starts_with($update->callbackQuery->data, CallbackData::INCOME_ANTICOR)) {
            $callbackData = $update->callbackQuery->data;
            $count = DB::table('messages')->where('type', MessageType::ANTICOR)->count();
            $limit = 5;
            $pageCount = floor($count / $limit);
            $text = '';

            // Set page
            if ($callbackData == CallbackData::INCOME_ANTICOR_FIRST) $currentPage = 0;
            elseif ($callbackData == CallbackData::INCOME_ANTICOR_LAST) $currentPage = $pageCount;
            else $currentPage = intval(str_replace(CallbackData::INCOME_ANTICOR, '', $callbackData));

            $offset = $currentPage * 5;

            // TODO(): Extract to class const
            switch ($user->language) {
                case Languages::RU:
                {
                    $cancelText = '🔙 На главную';
                    break;
                }
                case Languages::UZ:
                {
                    $cancelText = '🔙 Bosh sahifaga';
                    break;
                }
                default:
                {
                    $cancelText = '🔙 Home';
                    break;
                }
            }

            // Buttons
            $firstPage = new InlineButton('⏪', CallbackData::INCOME_ANTICOR_FIRST);
            $previousPage = new InlineButton('◀️', CallbackData::INCOME_ANTICOR . ($currentPage - 1));
            $current = new InlineButton($currentPage + 1 . '/' . $pageCount + 1, CallbackData::BLANK);
            $nextPage = new InlineButton('▶️', CallbackData::INCOME_ANTICOR . ($currentPage + 1));
            $lastPage = new InlineButton('⏩', CallbackData::INCOME_ANTICOR_LAST);
            $cancelButton = new InlineButton($cancelText, CallbackData::CANCEL);

            $collection = DB::table('messages')->where('type', MessageType::ANTICOR)->limit($limit)->offset($offset)->get();
            foreach ($collection as $item) {
                $formattedDate = date('H:i d.m.Y', strtotime($item->created_at));
                $text .= "*ID: " . $item->id . "*\n" . $item->text . "\n" . $formattedDate . "\n\n\n";
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
            }

            if (isset($update->callbackQuery->message->chat->id)) {
                $this->httpService->reactToCallback($update);
            }

            $this->httpService->sendMessage(
                $user->chat_id,
                $text,
                [
                    array_values(array_filter([
                        // Если страница не первая, добавляем кнопки "⏪" и "◀️"
                        $currentPage != 0 ? $firstPage->toArray() : null,
                        $currentPage != 0 ? $previousPage->toArray() : null,

                        // Всегда отображаем кнопку с текущей страницей
                        $current->toArray(),

                        // Если страница не последняя, добавляем кнопки "▶️" и "⏩"
                        $currentPage < $pageCount ? $nextPage->toArray() : null,
                        $currentPage < $pageCount ? $lastPage->toArray() : null,
                    ])),
                    // Добавляем кнопку отмены
                    [$cancelButton->toArray()],
                ]);
        }
    }
}
