<?php

namespace App\Http\Controllers;

use App\Models\Languages;
use App\Models\Users;
use Illuminate\Support\Facades\Http;

class HomeController
{
    public function index(Users $user): void
    {
        if ($user->language == Languages::RU) {
            $text = '👋 Привет! Вы находитесь в официальном боте Узбек Гидро Энерго.

Здесь вы можете:

📩 Обратиться с предложением или вопросом — мы всегда готовы рассмотреть ваши идеи и помочь с любыми вопросами.
⚖️ Сообщить о коррупции — если вы столкнулись с неправомерными действиями, пожалуйста, дайте нам знать. Ваше обращение останется конфиденциальным.
🌐 Сменить язык — выберите язык бота.

Чтобы начать, просто выберите нужный пункт из меню ниже.';

            $request = '📩 Обратиться с предложением или вопросом';
            $corruption = '⚖️ Сообщить о коррупции';
            $language = '🌐 Сменить язык';
        } elseif ($user->language == Languages::UZ) {
            $text = '👋 Salom! Siz O\'zbek Gidro Energo kompaniyasining rasmiy botidasiz.

Bu yerda siz:

📩 Taklif yoki savol bo\'yicha biz bilan bog\'lanishingiz mumkin - biz sizning taklifingizni ko\'rib chiqishga va har qanday savol bo\'yicha yordam berishga doim tayyormiz.
⚖️ Korrupsiya haqida xabar berishingiz mumkin - agar siz noto\'g\'ri xatti-harakatlarga duch kelsangiz, bizga xabar bering. Sizning murojaatingiz maxfiy saqlanib, ko\'rib chiqiladi.
🌐 Tilni o\'zgartirish - bot tilini tanlang.

Boshlash uchun quyidagi menyudan biror bandni tanlang.';

            $request = '📩 Taklif yoki savol';
            $corruption = '⚖️ Korrupsiya haqida xabar berish';
            $language = '🌐 Tilni o\'zgartirish';
        } else {
            $text = '👋 Hello! You are in the official bot of Uzbek Hydro Energy.

Here you can:

📩 Contact us with a suggestion or question - we are always ready to consider your ideas and help with any questions.
⚖️ Report Corruption - If you experience misconduct, please let us know. Your request will remain confidential.
🌐 Change language - select the bot language.

To get started, simply select an item from the menu below.';

            $request = '📩 Suggestion or question';
            $corruption = '⚖️ Report Corruption';
            $language = '🌐 Change language';
        }


        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
            'chat_id' => $user->chat_id,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $request, 'callback_data' => 'request']],
                    [['text' => $corruption, 'callback_data' => 'corruption']],
                    [['text' => $language, 'callback_data' => 'language']]
                ]
            ]),
        ]);
    }
}
