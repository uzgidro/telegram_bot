<?php

namespace App\Dao;

use App\Models\Destinations;
use App\Models\Languages;
use App\Models\MessageTG;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UsersDao
{

    /**
     * @param int $id
     * @return bool
     */
    public function userDoesNotExist(int $id): bool
    {
        return DB::table('users')->where('chat_id', $id)->doesntExist();
    }

    /**
     * @param MessageTG $message
     * @param Languages $language
     * @return void
     */
    public function createNewUser(MessageTG $message, Languages $language): void
    {
        DB::table('users')->insert([
            'chat_id' => $message->chat->id,
            'first_name' => $message->from->firstName,
            'last_name' => $message->from->lastName,
            'username' => $message->from->username,
            'destination' => Destinations::HOME,
            'language' => $language,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @param int $id
     * @return Model|Builder|object|null
     */
    public function getUser(int $id)
    {
        return DB::table('users')->where('chat_id', $id)->first();
    }

    /**
     * @param int $id
     * @param string $destination
     * @return void
     */
    public function setDestination(int $id, string $destination): void
    {
        DB::table('users')
            ->where('chat_id', $id)
            ->where('destination', '!=', $destination)
            ->update(['destination' => $destination]);
    }

    public function setLanguage(int $id, string $language): void
    {
        DB::table('users')->where('chat_id', $id)
            ->update([
                'language' => $language,
                'destination' => Destinations::HOME
            ]);
    }
}
