<?php

namespace App\Dao;

use App\Models\UpdateTG;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MessagesDao
{
    /**
     * @param UpdateTG $update
     * @param string $type
     * @return void
     */
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

    /**
     * @param string $messageType
     * @return int
     */
    public function getMessagesCount(string $messageType): int
    {
        return DB::table('messages')->where('type', $messageType)->count();
    }

    /**
     * @param string $messageType
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function getMessages(string $messageType, int $limit = 5, int $offset = 0): Collection
    {
        return DB::table('messages')
            ->where('type', $messageType)
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
