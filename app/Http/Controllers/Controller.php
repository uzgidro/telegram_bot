<?php

namespace App\Http\Controllers;

use App\Models\CallbackData;
use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public HomeController $homeController;
    public LanguageController $languageController;
    public CommandController $commandController;
    public CallbackController $callbackController;

    /**
     * @param HomeController $homeController
     * @param LanguageController $languageController
     * @param CommandController $commandController
     * @param CallbackController $callbackController
     */
    public function __construct(
        HomeController $homeController,
        LanguageController $languageController,
        CommandController $commandController,
        CallbackController $callbackController,
    )
    {
        $this->homeController = $homeController;
        $this->languageController = $languageController;
        $this->commandController = $commandController;
        $this->callbackController = $callbackController;
    }


    /**
     * @param array $update
     * @return void
     */
    public function parseUpdate(array $update): void
    {
        $model = new UpdateTG($update);

        if (isset($model->message->entities->type) && $model->message->entities->type == 'bot_command') {
            $this->commandController->index($model);
        }
        if (isset($model->callbackQuery->data)) {
            $this->callbackController->index($model);
        }
    }

}
