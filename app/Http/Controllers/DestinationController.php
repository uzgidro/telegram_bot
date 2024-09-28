<?php

namespace App\Http\Controllers;

use App\Constants\CallbackData;
use App\Constants\Destinations;
use App\Constants\MessageType;
use App\Models\UpdateTG;
use App\Models\Users;

class DestinationController
{
    private HomeController $homeController;
    private AnticorController $anticorController;
    private MurojaatController $murojaatController;
    private LanguageController $languageController;
    private AdminController $adminController;

    /**
     * @param HomeController $homeController
     * @param AnticorController $anticorController
     * @param LanguageController $languageController
     * @param MurojaatController $murojaatController
     * @param AdminController $adminController
     */
    public function __construct(
        HomeController     $homeController,
        AnticorController  $anticorController,
        LanguageController $languageController,
        MurojaatController $murojaatController,
        AdminController    $adminController
    )
    {
        $this->homeController = $homeController;
        $this->anticorController = $anticorController;
        $this->languageController = $languageController;
        $this->murojaatController = $murojaatController;
        $this->adminController = $adminController;
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
        if ($user->destination == Destinations::ANTICOR_ADMIN) {
            $this->adminController->receiveIncomeMessages(
                $user,
                $update,
                CallbackData::INCOME_ANTICOR,
                MessageType::ANTICOR
            );
        }
        if ($user->destination == Destinations::MUROJAAT_ADMIN) {
            $this->adminController->receiveIncomeMessages(
                $user,
                $update,
                CallbackData::INCOME_MUROJAAT,
                MessageType::MUROJAAT
            );
        }
        if ($user->destination == Destinations::LANGUAGE) {
            $this->languageController->index($user, $update);
        }
    }
}
