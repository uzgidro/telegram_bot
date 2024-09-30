<?php

use App\Constants\MessageType;
use App\Http\Controllers\Controller;
use App\Http\service\HttpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function (HttpService $httpService, Controller $controller) {
    $response = $httpService->getUpdates();
    $lastUpdate = $response->json()['result'][count($response->json()['result'])-1];
    $controller->parseUpdate($lastUpdate);
});

Route::get('/dd', function (HttpService $httpService) {
    $response = $httpService->getUpdates();
    $lastUpdate = $response->json();
    dd($lastUpdate);
});

Route::post('/bot', [Controller::class, 'parseUpdateRequest']);

Route::get('/api/getMessages', function (Request $request) {
    return json_encode(
        DB::table('messages')
            ->get()
    );
});

Route::get('/api/getMessagesByType', function (Request $request) {
    switch ($request->string('type')) {
        case MessageType::ANTICOR: {
            $type = MessageType::ANTICOR;
            break;
        }
        case MessageType::MUROJAAT: {
            $type = MessageType::MUROJAAT;
            break;
        }
        default: {
            return [];
        }
    }

    return json_encode(
        DB::table('messages')
            ->join('users', 'users.chat_id', '=', 'messages.chat_id')
            ->select('messages.id', DB::raw("CONCAT(users.first_name, ' ', COALESCE(users.last_name, '')) as name"), 'messages.text', 'messages.created_at')
            ->where('messages.type', $type)
            ->get()
    );
});

Route::get('/api/getById', function (Request $request) {
    return json_encode(
        DB::table('messages')
            ->join('users', 'users.chat_id', '=', 'messages.chat_id')
            ->select('messages.id', DB::raw("CONCAT(users.first_name, ' ', COALESCE(users.last_name, '')) as name"), 'messages.text', 'messages.created_at')
            ->where('messages.id', $request->integer('id'))
            ->get()
    );
});
