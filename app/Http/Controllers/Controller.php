<?php

namespace App\Http\Controllers;

use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function parseUpdate(array $update): void
    {
//        dd($update);
        $model = new UpdateTG($update);


        Users::firstOrCreate([
            'chat_id' => $model->message->chat->id,
            'first_name' => $model->message->from->firstName,
            'last_name' => $model->message->from->lastName,
            'username' => $model->message->from->username,
        ]);
        dd(Users::all());
    }

}
