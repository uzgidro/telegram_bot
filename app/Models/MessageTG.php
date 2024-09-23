<?php

namespace App\Models;

class MessageTG
{
    public int $id;
    public ?UserTG $from;
    public ?ChatTG $chat;
    public ?string $text;
    public ?MessageEntitiesTG $entities;

    /**
     * @param mixed $request
     */
    public function __construct(mixed $request)
    {
        if ($request === null) {
            return;
        }

        $this->id = $request['message_id'];
        $this->from = new UserTG($request['from'] ?? null);
        $this->chat = new ChatTG($request['chat'] ?? null);
        $this->text = $request['text'] ?? null;
        $this->entities = new MessageEntitiesTG($request['entities'][0] ?? null);
    }


}
