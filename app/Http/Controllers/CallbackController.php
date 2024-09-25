<?php

namespace App\Http\Controllers;

use App\Dao\UsersDao;
use App\Models\CallbackData;
use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;

class CallbackController
{
    private UsersDao $dao;
    private DestinationController $destinationController;

    /**
     * @param UsersDao $dao
     * @param DestinationController $destinationController
     */
    public function __construct(UsersDao $dao, DestinationController $destinationController)
    {
        $this->dao = $dao;
        $this->destinationController = $destinationController;
    }


    /**
     * @param UpdateTG $update
     * @return void
     */
    public function index(UpdateTG $update): void
    {
        if ($update->callbackQuery->data == CallbackData::HOME_LANGUAGE) {
            $this->dao->setDestination($update->callbackQuery->message->chat->id, Destinations::LANGUAGE);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_RU) {
            $this->dao->setLanguage($update->callbackQuery->message->chat->id, Languages::RU);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_UZ) {
            $this->dao->setLanguage($update->callbackQuery->message->chat->id, Languages::UZ);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_EN) {
            $this->dao->setLanguage($update->callbackQuery->message->chat->id, Languages::EN);
        }
        if ($update->callbackQuery->data == CallbackData::LANGUAGE_CANCEL) {
            $this->dao->setDestination($update->callbackQuery->message->chat->id, Destinations::HOME);
        }
        $this->destinationController->index(Users::createFromData($this->dao->getUser($update->callbackQuery->message->chat->id)), $update);
    }
}
