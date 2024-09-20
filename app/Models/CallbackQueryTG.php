<?php

namespace App\Models;

class CallbackQueryTG
{
    public string $id;
    public ?MessageTG $message;
    public ?string $data;

    /**
     * @param string $id
     * @param MessageTG $message
     * @param string $data
     */
    public function __construct(mixed $request)
    {
        if ($request === null) {
            return;
        }

        $this->id = $request['id'];
        $this->message = new MessageTG($request['message'] ?? null);
        $this->data = $request['data'] ?? null;
    }


}
