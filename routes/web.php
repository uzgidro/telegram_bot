<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function (Controller $controller) {
//    dd('Hello World!');

    $response = Http::get('https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s/getUpdates');
    $lastUpdate = $response->json()['result'][count($response->json()['result'])-1];
    $controller->parseUpdate($lastUpdate);
//    dd($response->json()['result'][count($response->json()['result'])-1]);
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

    $data = $request->all();

    if ($request->input('message.text') == '/start') {
        if(DB::table('users')->first('chat_id') === null) {
            Db::table('users')->insert([
                'chat_id' => $data['message']['chat']['id'],
                'username' => $data['message']['from']['username'],
                'first_name' => $data['message']['from']['first_name'],
                'last_name' => $data['message']['from']['last_name'],
            ]);
        }

        Http::post('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage', [
            'chat_id' => $request->input('message.chat.id'),
            'text' => DB::table('users')->get(),
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

    Http::post('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage',
        ['chat_id' => $request->input('message.chat.id'),
            'text' => 'Благодарим Вас за Ваш отзыв, в ближайшее время с вами свяжутся']
    );
});
