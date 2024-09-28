<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\InlineButton;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminController
{
    private HttpService $httpService;
    private const LIMIT = 5;
    private const POSTFIX_FIRST = '_FIRST';
    private const POSTFIX_LAST = '_LAST';

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
     * @param string $callbackType
     * @param string $messageType
     * @return void
     */
    public function receiveIncomeMessages(
        Users     $user,
        ?UpdateTG $update,
        string    $callbackType,
        string    $messageType,
    ): void
    {
        if (str_starts_with($update->callbackQuery->data, $callbackType)) {
            $callbackData = $update->callbackQuery->data;
            $count = DB::table('messages')->where('type', $messageType)->count();
            $pageCount = floor($count / self::LIMIT);

            // Set page
            if ($callbackData == $callbackType . self::POSTFIX_FIRST) $currentPage = 0;
            elseif ($callbackData == $callbackType . self::POSTFIX_LAST) $currentPage = $pageCount;
            else $currentPage = intval(str_replace($callbackType, '', $callbackData));

            $offset = $currentPage * 5;

            $cancelText = $this->setCancelText($user);

            // Buttons
            $firstPage = new InlineButton('⏪', $callbackType . self::POSTFIX_FIRST);
            $previousPage = new InlineButton('◀️', $callbackType . ($currentPage - 1));
            $current = new InlineButton($currentPage + 1 . '/' . $pageCount + 1, CallbackData::BLANK);
            $nextPage = new InlineButton('▶️', $callbackType . ($currentPage + 1));
            $lastPage = new InlineButton('⏩', $callbackType . self::POSTFIX_LAST);
            $cancelButton = new InlineButton($cancelText, CallbackData::CANCEL);

            $collection = DB::table('messages')->where('type', $messageType)->limit(self::LIMIT)->offset($offset)->get();
            $text = $this->setText($collection, $user->language);

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

    /**
     * @param Users $user
     * @return string
     */
    public function setCancelText(Users $user): string
    {
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
        return $cancelText;
    }

    /**
     * @param Collection $collection
     * @param string $language
     * @return string
     */
    private function setText(Collection $collection, string $language)
    {
        $text = '';
        if (count($collection) > 0) {
            foreach ($collection as $item) {
                $formattedDate = date('H:i d.m.Y', strtotime($item->created_at));
                $text .= "*ID: " . $item->id . "*\n" . $item->text . "\n" . $formattedDate . "\n\n\n";
            }
        } else {
            $text = $this->setBlankText($language);
        }
        return $text;
    }

    /**
     * @param string $language
     * @return string
     */
    private function setBlankText(string $language): string
    {
        switch ($language) {
            case Languages::RU:
            {
                return 'Список пуст';
            }
            case Languages::UZ:
            {
                return 'Ro\'yxat bo\'sh';
            }
            default:
            {
                return 'List is empty';
            }
        }
    }
}
