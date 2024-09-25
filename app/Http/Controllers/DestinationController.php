<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\UpdateTG;
use App\Models\Users;

class DestinationController
{
    private HomeController $homeController;
    private LanguageController $languageController;

    /**
     * @param HomeController $homeController
     * @param LanguageController $languageController
     */
    public function __construct(HomeController $homeController, LanguageController $languageController)
    {
        $this->homeController = $homeController;
        $this->languageController = $languageController;
    }


    /**
     * @param Users $user
     * @param UpdateTG|null $update
     * @return void
     */
    protected function onDestination(Users $user, ?UpdateTG $update = null): void
    {
        if ($user->destination == Destinations::HOME) {
            $this->homeController->index($user, $update);
        }
        if ($user->destination == Destinations::LANGUAGE) {
            $this->languageController->index($user, $update);
        }
    }
}
