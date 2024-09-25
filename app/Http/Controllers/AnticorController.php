<?php

namespace App\Http\Controllers;

use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\Http;

class AnticorController
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

Если вы столкнулись со случаями коррупции (злоупотребление служебным положением, взяточничество, жадность, мошенничество и т.п.) в сфере гидроэнергетики, вы можете сообщить о них через этого бота.

Для того чтобы заявка была рассмотрена полностью и всесторонне, Вам необходимо заполнить следующую информацию.

1. Ф.И.О;
2. Ваш номер телефона;
3. От какого района или региона вы подаете заявление?
4. Содержание заявления

Для связи с АО «Узбекгидроэнерго»:
- Канцелярия: 71 241-34-21.
- Горячая линия: 78 150-50-15
- Телефон антикоррупционной службы: 78 150-50-35.
- Адрес: 100011, Ташкентское ш., улица Навои, 22.
- Сайт: uzgidro.uz
- Электронная почта: devonxona@uzgidro.uz

-Рабочее время: 09:00-18:00 (Обед: 13:00-14:00)
-Рабочие дни: понедельник-пятница.';

            $cancel = '🔙 Отмена';
        } elseif ($user->language == Languages::UZ) {
            $text = 'Assalomu alaykum!

Agar Siz gidroenergetika sohasida  korrupsiya holatlariga (mansabini suiiste’mol qilish, poraxo‘rlik, tamagirlik, firibgarlik va x.k.) duch kelgan bo‘lsangiz, ular haqida ushbu bot orqali xabar berishingiz mumkin.

Murojaat to‘liq va atroflicha ko‘rib chiqilishi uchun quyidagi ma’lumotlarni to‘ldirishingiz lozim.

1. F.I.Sh;
2. Telefon raqamingiz;
3. Qaysi tuman yoki hudud bo‘yicha murojaat qilyapsiz?
4. Murojaat mazmuni

"O`zbekgidroenergo" AJ bilan bog‘lanish uchun:
-Devonxona raqami: 71 241-34-21
-Ishonch telefoni:  78  150-50-15
-Korrupsiyaga qarshi kurashish xizmati telefoni: 78 150-50-35
-Manzil: 100011, Tashkent sh., Navoiy  ko‘chasi, 22.
-Veb-sayt: uzgidro.uz
-Elektron pochta:  devonxona@uzgidro.uz

-Ish vaqti: 09:00-18:00 (Tushlik: 13:00-14:00)
-Ish kunlari: Dushanba - Juma';

            $cancel = '🔙 Bekor qilish';
        } else {
            $text = 'Greetings!

If you have encountered cases of corruption (abuse of office, bribery, greed, fraud, etc.) in the field of hydropower, you can report them through this bot.

In order for the application to be considered completely and comprehensively, you need to fill in the following information.

1. First name, Last name;
2. Your phone number;
3. Which district or region are you applying for?
4. Content of the application

To contact "Uzbekgidroenergo" JSC:
- Office number: 71 241-34-21
- Hotline: 78 150-50-15
- Anti-corruption service phone number: 78 150-50-35
- Address: 100011, Tashkent sh., Navoi street, 22.
- Website: uzgidro.uz
- Email: devonxona@uzgidro.uz

-Working hours: 09:00-18:00 (Lunch: 13:00-14:00)
-Working days: Monday - Friday';

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
            $text = 'Спасибо за ваше обращение о коррупции. Мы приняли вашу информацию к сведению и передали ее в соответствующие органы для дальнейшего расследования.
Мы ценим вашу активную позицию и готовность бороться с коррупцией.';
            $cancel = '🔙 На главную';
        } elseif ($user->language == Languages::UZ) {
            $text = 'Korrupsiya haqidagi xabaringiz uchun tashakkur. Biz sizning murojaatingizni hisobga oldik va tekshiruv ishlari uchun tegishli organlarga yubordik.
Sizning faolligingiz va korrupsiyaga qarshi kurashga tayyorligingizni qadrlaymiz.';
            $cancel = '🔙 Bosh sahifaga';
        } else {
            $text = 'Thank you for your message about corruption. We have taken note of your information and forwarded it to the appropriate authorities for further investigation.
We appreciate your active position and willingness to fight corruption.';
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
