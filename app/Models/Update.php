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
    public function __construct(array $request)
    {
        $this->updateId = $request['update_id'];
        $this->message = new Message($request['message']);
        $this->callbackQuery = new CallbackQuery($request['callback_query']);
    }


}
