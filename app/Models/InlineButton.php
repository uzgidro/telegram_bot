<?php

namespace App\Models;

class InlineButton
{
    public string $text;
    public string $callbackData;

    public function __construct($text, $callback_data)
    {
        $this->text = $text;
        $this->callbackData = $callback_data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'callback_data' => $this->callbackData
        ];
    }
}
