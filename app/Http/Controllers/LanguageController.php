<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\Http;

class LanguageController
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
    public function index(Users $user, ?UpdateTG $update): void
    {
        $ru = '🇷🇺 Русский';
        $uz = '🇺🇿 O\'zbek';
        $en = '🇺🇸 English';
        if ($user->language == Languages::RU) {
            $text = '🌐 Выберите язык бота.';
            $cancel = '🔙 Отмена';
        } elseif ($user->language == Languages::UZ) {
            $text = '🌐 Bot tilini tanlang.';
            $cancel = '🔙 Bekor qilish';
        } else {
            $text = '🌐 Select bot language.';
            $cancel = '🔙 Cancel';
        }

        if (isset($update)) {
            $this->httpService->reactToCallback($update);
        }

        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
            'chat_id' => $user->chat_id,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $ru, 'callback_data' => CallbackData::LANGUAGE_RU]],
                    [['text' => $uz, 'callback_data' => CallbackData::LANGUAGE_UZ]],
                    [['text' => $en, 'callback_data' => CallbackData::LANGUAGE_EN]],
                    [['text' => $cancel, 'callback_data' => CallbackData::LANGUAGE_CANCEL]],
                ]
            ]),
        ]);
    }
}
