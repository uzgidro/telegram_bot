<?php

namespace App\Models;

class InlineKeyboardButton
{
 public string $text;
 public string $callback_data;

    /**
     * @param string $text
     * @param string $callback_data
     */
    public function __construct(string $text, string $callback_data)
    {
        $this->text = $text;
        $this->callback_data = $callback_data;
    }


}
