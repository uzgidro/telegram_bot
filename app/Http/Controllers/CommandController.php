<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class CommandController extends DestinationController
{
    /**
     * @param UpdateTG $model
     * @return void
     */
    public function index(UpdateTG $model): void
    {
        if ($model->message->text == '/start') {
            $this->onStartCommand($model);
        }
        if ($model->message->text == '/home') {
            $this->onHomeCommand($model);
        }
    }


    /**
     * @param UpdateTG $model
     * @return void
     */
    private function onStartCommand(UpdateTG $model): void
    {
        // create user if not exists
        if (DB::table('users')->where('chat_id', $model->message->chat->id)->doesntExist()) {
            if ($model->message->from->languageCode !== Languages::RU
                && $model->message->from->languageCode !== Languages::UZ
                && $model->message->from->languageCode !== Languages::EN) {
                $language = Languages::EN;
            } else {
                $language = $model->message->from->languageCode;
            }
            DB::table('users')->insert([
                'chat_id' => $model->message->chat->id,
                'first_name' => $model->message->from->firstName,
                'last_name' => $model->message->from->lastName,
                'username' => $model->message->from->username,
                'destination' => Destinations::HOME,
                'language' => $language,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // go to home page
        DB::table('users')
            ->where('chat_id', $model->message->chat->id)
            ->where('destination', '!=', Destinations::HOME)
            ->update(['destination' => Destinations::HOME]);
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $model->message->chat->id)->first()));
    }

    /**
     * @param UpdateTG $model
     * @return void
     */
    private function onHomeCommand(UpdateTG $model): void
    {
        // go to home page
        DB::table('users')
            ->where('chat_id', $model->message->chat->id)
            ->where('destination', '!=', Destinations::HOME)
            ->update(['destination' => Destinations::HOME]);
        $this->onDestination(Users::createFromData(DB::table('users')->where('chat_id', $model->message->chat->id)->first()));
    }
}
