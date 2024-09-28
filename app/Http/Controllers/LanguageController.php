<?php

namespace App\Http\Controllers;

use App\Constants\Buttons;
use App\Constants\CallbackData;
use App\Constants\Languages;
use App\Http\service\HttpService;
use App\Models\InlineButton;
use App\Models\UpdateTG;
use App\Models\Users;

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
        } elseif ($user->language == Languages::UZ) {
            $text = '🌐 Bot tilini tanlang.';
        } else {
            $text = '🌐 Select bot language.';
        }

        if (isset($update->callbackQuery->id)) {
            $this->httpService->reactToCallback($update);
        }

        $ruButton = new InlineButton($ru, CallbackData::LANGUAGE_RU);
        $uzButton = new InlineButton($uz, CallbackData::LANGUAGE_UZ);
        $enButton = new InlineButton($en, CallbackData::LANGUAGE_EN);

        $this->httpService->sendMessage(
            $user->chat_id,
            $text,
            [
                [$ruButton->toArray()],
                [$uzButton->toArray()],
                [$enButton->toArray()],
                [Buttons::getCancelButton($user->language)],
            ]
        );
    }
}
