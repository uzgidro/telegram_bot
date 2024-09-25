<?php

namespace App\Http\Controllers;

use App\Dao\UsersDao;
use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;

class CommandController
{
    private UsersDao $dao;
    private DestinationController $destinationController;

    /**
     * @param UsersDao $dao
     * @param DestinationController $controller
     */
    public function __construct(UsersDao $dao, DestinationController $controller)
    {
        $this->dao = $dao;
        $this->destinationController = $controller;
    }


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
     * @param UpdateTG $update
     * @return void
     */
    private function onStartCommand(UpdateTG $update): void
    {
        // create user if not exists
        if ($this->dao->userDoesNotExist($update->message->chat->id)) {
            if ($update->message->from->languageCode !== Languages::RU
                && $update->message->from->languageCode !== Languages::UZ
                && $update->message->from->languageCode !== Languages::EN) {
                $language = Languages::EN;
            } else {
                $language = $update->message->from->languageCode;
            }
            $this->dao->createNewUser($update->message, $language);
        }

        // go to home page
        $this->dao->setDestination($update->message->chat->id, Destinations::HOME);
        $this->destinationController->index(Users::createFromData($this->dao->getUser($update->message->chat->id)));
    }

    /**
     * @param UpdateTG $update
     * @return void
     */
    private function onHomeCommand(UpdateTG $update): void
    {
        // go to home page
        $this->dao->setDestination($update->message->chat->id, Destinations::HOME);
        $this->destinationController->index(Users::createFromData($this->dao->getUser($update->message->chat->id)));
    }
}
