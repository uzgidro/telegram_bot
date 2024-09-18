<?php

namespace App\Models;

class MessageDto
{
    public string $chat_id;
    public string $text;
    public ReplyMarkup $reply_markup;

    /**
     * @param string $chat_id
     * @param string $text
     * @param ReplyMarkup $reply_markup
     */
    public function __construct(string $chat_id, string $text, ReplyMarkup $reply_markup)
    {
        $this->chat_id = $chat_id;
        $this->text = $text;
        $this->reply_markup = $reply_markup;
    }


}
