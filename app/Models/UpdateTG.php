<?php

namespace App\Models;

class UpdateTG
{
    public int $updateId;
    public ?MessageTG $message;
    public ?CallbackQueryTG $callbackQuery;

    /**
     * @param mixed $request
     */
    public function __construct(mixed $request)
    {
        if ($request === null) {
            return;
        }

        $this->updateId = $request['update_id'];
        $this->message = new MessageTG($request['message'] ?? null);
        $this->callbackQuery = new CallbackQueryTG($request['callback_query'] ?? null);
    }


}
