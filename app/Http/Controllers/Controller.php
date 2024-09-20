<?php

namespace App\Http\Controllers;

use App\Models\UpdateTG;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function parseUpdate(array $update)
    {
//        dd($update);
        $model = new UpdateTG($update);
        dd($model);
    }

}
