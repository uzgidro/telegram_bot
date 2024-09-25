<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\UpdateTG;
use App\Models\Users;

class DestinationController
{
    private HomeController $homeController;
    private AnticorController $annotationController;
    private LanguageController $languageController;

    /**
     * @param HomeController $homeController
     * @param AnticorController $annotationController
     * @param LanguageController $languageController
     */
    public function __construct(
        HomeController $homeController,
        AnticorController $annotationController,
        LanguageController $languageController
    )
    {
        $this->homeController = $homeController;
        $this->annotationController = $annotationController;
        $this->languageController = $languageController;
    }


    /**
     * @param Users $user
     * @param UpdateTG|null $update
     * @return void
     */
    public function index(Users $user, ?UpdateTG $update = null): void
    {
        if ($user->destination == Destinations::HOME) {
            $this->homeController->index($user, $update);
        }
        if ($user->destination == Destinations::ANTICOR_NEW_RECORD) {
            $this->annotationController->index($user, $update);
        }
        if ($user->destination == Destinations::LANGUAGE) {
            $this->languageController->index($user, $update);
        }
    }
}
