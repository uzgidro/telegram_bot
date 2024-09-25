<?php

namespace App\Http\service;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpService
{
    private const BASE_URL = 'https://api.telegram.org/bot7849210506:AAHwUp5nF6nWxxfEoEH8NVBP6CwyRtHUx7s';

    /**
     * @return Response
     */
    public function getUpdates(): Response
    {
        return Http::get(self::BASE_URL.'/getUpdates');
    }
}
