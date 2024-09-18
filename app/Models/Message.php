<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message
{
    public int $messageId;
    public Chat $chat;
    public string $text;

    /**
     * @param int $messageId
     * @param Chat $chat
     * @param string $text
     */
    public function __construct(mixed $request)
    {
        $this->messageId = $request->{'messageId'};
        $this->chat = new Chat($request->{'chat'});
        $this->text = $request->{'text'};
    }


}
