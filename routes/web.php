<?php

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

    if ($request->input('message.text') == '/start') {
        Http::get('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage',
            ['chat_id' => $request->input('message.chat.id'),
                'text' => 'Добро пожаловать в антикоррупционный бот АО"Узбекгидроэнерго", вы столкнулись со случаями коррупции - оставьте ваш отзыв и он будет рассмотрен в установленном порадке']
        );
        return;
    }

    Http::get('https://api.telegram.org/bot6676964221:AAFko2aqnMbC-YeviA4ZRutfEQY1pr5P8Z8/sendMessage',
        ['chat_id' => $request->input('message.chat.id'),
            'text' => 'Благодарим Вас за Ваш отзыв, в ближайшее время с вами свяжутся']
    );
});
