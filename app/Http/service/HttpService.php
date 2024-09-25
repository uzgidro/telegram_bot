<?php

namespace App\Http\service;

use App\Models\MessageTG;
use App\Models\UpdateTG;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpService
{
    private const BASE_URL = 'https://api.telegram.org/bot';
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = self::BASE_URL.env('TG_KEY');
    }


    /**
     * @return Response
     */
    public function getUpdates(): Response
    {
        return Http::get($this->apiUrl.'/getUpdates');
    }

    /**
     * @param UpdateTG $update
     * @return void
     */
    public function reactToCallback(UpdateTG $update): void
    {
        $this->answerCallbackQuery($update->callbackQuery->id);
        $this->deleteMessage($update->callbackQuery->message);
    }

    /**
     * @param int $id
     * @return void
     */
    private function answerCallbackQuery(int $id): void
    {
        Http::post($this->apiUrl.'/answerCallbackQuery', [
            'callback_query_id' => $id,
        ]);
    }

    /**
     * @param MessageTG $message
     * @return void
     */
    private function deleteMessage(MessageTG $message): void
    {
        Http::post($this->apiUrl.'/deleteMessage', [
            'chat_id' => $message->chat->id,
            'message_id' => $message->id,
        ]);
    }
}
