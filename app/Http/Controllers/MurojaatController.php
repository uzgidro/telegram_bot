<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\Http;

class MurojaatController
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
        if ($user->language == Languages::RU) {
            $text = 'Здравствуйте!

Если у вас есть вопросы по деятельности организации или предложения для улучшения, то вы можете сообщить о них через этого бота.

Для того чтобы заявка была рассмотрена полностью и всесторонне, Вам необходимо заполнить следующую информацию.
1. 📝 Ф.И.О;
2. 📞 Ваш номер телефона;
3. 📍 От какого района или региона вы подаете заявление?
4. 📄 Содержание заявления

Для связи с АО «Узбекгидроэнерго»:
- 🏢 Канцелярия: 71 241-34-21.
- 📞 Горячая линия: 78 150-50-15
- 🚨 Телефон антикоррупционной службы: 78 150-50-35.
- 🏠 Адрес: 100011, Ташкентское ш., улица Навои, 22.
- 🌐 Сайт: uzgidro.uz
- 📧 Электронная почта: devonxona@uzgidro.uz

- ⏰ Рабочее время: 09:00-18:00 (Обед: 13:00-14:00)
- 📅 Рабочие дни: понедельник-пятница.';

            $cancel = '🔙 Отмена';
        } elseif ($user->language == Languages::UZ) {
            $text = 'Assalomu alaykum!

Tashkilot faoliyatiga oid savollaringiz yoki takomillashtirish bo\'yicha takliflaringiz bo\'lsa, ushbu bot orqali xabar berishingiz mumkin.

Murojaat to‘liq va atroflicha ko‘rib chiqilishi uchun quyidagi ma’lumotlarni to‘ldirishingiz lozim.
1. 📝 F.I.Sh;
2. 📞 Telefon raqamingiz;
3. 📍 Qaysi tuman yoki hudud bo‘yicha murojaat qilyapsiz?
4. 📄 Murojaat mazmuni

"O`zbekgidroenergo" AJ bilan bog‘lanish uchun:
- 🏢 Devonxona raqami: 71 241-34-21
- 📞 Ishonch telefoni:  78  150-50-15
- 🚨 Korrupsiyaga qarshi kurashish xizmati telefoni: 78 150-50-35
- 🏠 Manzil: 100011, Tashkent sh., Navoiy  ko‘chasi, 22.
- 🌐 Veb-sayt: uzgidro.uz
- 📧 Elektron pochta:  devonxona@uzgidro.uz

- ⏰ Ish vaqti: 09:00-18:00 (Tushlik: 13:00-14:00)
- 📅 Ish kunlari: Dushanba - Juma';

            $cancel = '🔙 Bekor qilish';
        } else {
            $text = 'Greetings!

If you have questions about the organization’s activities or suggestions for improvement, you can report them through this bot.

In order for the application to be considered completely and comprehensively, you need to fill in the following information.
1. 📝 First name, Last name;
2. 📞 Your phone number;
3. 📍 Which district or region are you applying for?
4. 📄 Content of the application

To contact "Uzbekgidroenergo" JSC:
- 🏢 Office number: 71 241-34-21
- 📞 Hotline: 78 150-50-15
- 🚨 Anti-corruption service phone number: 78 150-50-35
- 🏠 Address: 100011, Tashkent sh., Navoi street, 22.
- 🌐 Website: uzgidro.uz
- 📧 Email: devonxona@uzgidro.uz

- ⏰ Working hours: 09:00-18:00 (Lunch: 13:00-14:00)
- 📅 Working days: Monday - Friday';

            $cancel = '🔙 Cancel';
        }

        if (isset($update->callbackQuery->id)) {
            $this->httpService->reactToCallback($update);
        }

        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
            'chat_id' => $user->chat_id,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $cancel, 'callback_data' => CallbackData::CANCEL]],
                ]
            ]),
        ]);
    }

    public function newRecord(Users $user): void
    {
        if ($user->language == Languages::RU) {
            $text = 'Спасибо за ваше обращение! Мы ценим ваше время и заботу. Ваш запрос успешно получен, и мы постараемся ответить на него как можно скорее.';
            $cancel = '🔙 На главную';
        } elseif ($user->language == Languages::UZ) {
            $text = 'Murojaatingiz uchun rahmat! Vaqtingiz va tashvishingizni qadrlaymiz. Sizning so\'rovingiz muvaffaqiyatli qabul qilindi va biz unga imkon qadar tezroq javob berishga harakat qilamiz.';
            $cancel = '🔙 Bosh sahifaga';
        } else {
            $text = 'Thank you for your request! We appreciate your time and concern. Your request has been successfully received and we will try to respond to it as soon as possible.';
            $cancel = '🔙 Home';
        }

        Http::post('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/sendMessage', [
            'chat_id' => $user->chat_id,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $cancel, 'callback_data' => CallbackData::CANCEL]],
                ]
            ]),
        ]);
    }
}
