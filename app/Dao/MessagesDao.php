<?php

namespace App\Dao;

use App\Models\UpdateTG;
use Illuminate\Support\Facades\DB;

class MessagesDao
{
    public function createNewRecord(UpdateTG $update, string $type): void
    {
        DB::table('messages')->insert([
            'chat_id' => $update->message->chat->id,
            'text' => $update->message->text,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
