<?php

namespace App\Models;

class Update
{
    public int $updateId;
    public Message $message;
    public CallbackQuery $callbackQuery;

    /**
     * @param int $updateId
     * @param Message $message
     * @param CallbackQuery $callbackQuery
     */
    public function __construct(mixed $request)
    {
        $this->updateId = $request->{'updateId'};
        $this->message = new Message($request->{'message'});
        $this->callbackQuery = new CallbackQuery($request->{'callback_query'});
    }


}
