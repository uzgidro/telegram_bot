<?php

use App\Models\InlineKeyboardButton;
use App\Models\MessageDto;
use App\Models\Update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    dd('Hello World!');
});

Route::post('/murojaat', function (Request $request) {

    if ($request->input('message.text') == '/start') {
        Http::get('https://api.telegram.org/bot7232150649:AAGAEPVTJOV3uLi0ItgztJn4rJoa6iamL2s/sendMessage',
            ['chat_id' => $request->input('message.chat.id'),
                'text' => 'Добро пожаловать в бот вопросов и предложений АО"Узбекгидроэнерго", оставьте ваш вопрос, предложение или отзыв']
        );
        return;
    }

    Http::get('https://api.telegram.org/bot7232150649:AAGAEPVTJOV3uLi0ItgztJn4rJoa6iamL2s/sendMessage',
        ['chat_id' => $request->input('message.chat.id'),
            'text' => 'Благодарим Вас за Ваш отзыв, в ближайшее время с вами свяжутся']
    );
});


Route::post('/anticor', function (Request $request) {

//    $update = new Update($request->input());

    $data = $request->all();
    if(isset($data['callback_query'])) {
        $callbackQuery = $data['callback_query'];
        $callbackData = $callbackQuery['data']; // Получаем callback_data (например, 'start' или 'end')
        $chatId = $callbackQuery['message']['chat']['id']; // Получаем chat_id
        $messageId = $callbackQuery['message']['message_id']; // Получаем chat_id
        $callbackId = $callbackQuery['id']; // Получаем id callback-запроса

        // Обрабатываем данные callback-запроса
        if ($callbackData === 'start') {
            $responseText = "Вы нажали на кнопку Start!";
        } elseif ($callbackData === 'end') {
            $responseText = "Вы нажали на кнопку End!";
        } else {
            $responseText = "Неизвестная команда!";
        }
        // Отправляем ответ на callback_query (чтобы бот не висел)
        Http::post("https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/answerCallbackQuery", [
            'callback_query_id' => $callbackId,
        ]);

        Http::post('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);

        // Отправляем сообщение в чат с результатом нажатия кнопки
        Http::post("https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage", [
            'chat_id' => $chatId,
            'text' => $responseText,
        ]);


        return;
    }


    if ($request->input('message.text') == '/start') {
        Http::post('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage', [
            'chat_id' => $request->input('message.chat.id'),
            'text' => $request->getContent(),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '1', 'callback_data' => 'start'],
                        ['text' => '2', 'callback_data' => 'end']
                    ]
                ]
            ]),
        ]);
        return;
    }

    Http::post('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage',
        ['chat_id' => $request->input('message.chat.id'),
            'text' => 'Благодарим Вас за Ваш отзыв, в ближайшее время с вами свяжутся']
    );
});
