<?php

namespace App\Models;

class ReplyMarkup
{
    public array $inline_keyboard;

    /**
     * @param array $inline_keyboard
     */
    public function __construct(array $inline_keyboard)
    {
        $this->inline_keyboard = $inline_keyboard;
    }


}
