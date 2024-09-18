<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackQuery
{
    public string $id;
    public Message $message;
    public string $data;

    /**
     * @param string $id
     * @param Message $message
     * @param string $data
     */
    public function __construct(mixed $request)
    {
        $this->id = $request->{'id'};
        $this->message = new Message($request->{'message'});
        $this->data = $request->{'data'};
    }


}
