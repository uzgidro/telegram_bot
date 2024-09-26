<?php

namespace App\Http\Controllers;

use App\Models\Destinations;
use App\Models\UpdateTG;
use App\Models\Users;

class DestinationController
{
    private HomeController $homeController;
    private AnticorController $anticorController;
    private MurojaatController $murojaatController;
    private LanguageController $languageController;

    /**
     * @param HomeController $homeController
     * @param AnticorController $anticorController
     * @param LanguageController $languageController
     * @param MurojaatController $murojaatController
     */
    public function __construct(
        HomeController $homeController,
        AnticorController $anticorController,
        LanguageController $languageController,
        MurojaatController $murojaatController,
    )
    {
        $this->homeController = $homeController;
        $this->anticorController = $anticorController;
        $this->languageController = $languageController;
        $this->murojaatController = $murojaatController;
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
            $this->anticorController->index($user, $update);
        }
        if ($user->destination == Destinations::MUROJAAT_NEW_RECORD) {
            $this->murojaatController->index($user, $update);
        }
        if ($user->destination == Destinations::LANGUAGE) {
            $this->languageController->index($user, $update);
        }
    }
}
