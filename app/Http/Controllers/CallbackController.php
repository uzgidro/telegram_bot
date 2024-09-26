<?php

namespace App\Http\Controllers;

use App\Dao\UsersDao;
use App\Http\service\HttpService;
use App\Models\CallbackData;
use App\Models\CallbackQueryTG;
use App\Models\Destinations;
use App\Models\Languages;
use App\Models\UpdateTG;
use App\Models\Users;

class CallbackController
{
    private UsersDao $dao;
    private HttpService $httpService;
    private DestinationController $destinationController;

    /**
     * @param UsersDao $dao
     * @param DestinationController $destinationController
     */
    public function __construct(
        UsersDao $dao,
        HttpService $httpService,
        DestinationController $destinationController
    )
    {
        $this->dao = $dao;
        $this->httpService = $httpService;
        $this->destinationController = $destinationController;
    }


    /**
     * @param UpdateTG $update
     * @return void
     */
    public function index(UpdateTG $update): void
    {
        if ($update->callbackQuery->data == CallbackData::BLANK) {
            $this->httpService->answerCallbackQuery($update->callbackQuery->id);
            return;
        }
        $this->onHomeScreenSelect($update->callbackQuery);
        $this->onLanguageSelect($update->callbackQuery);
        $this->onAdminSelected($update->callbackQuery);
        if ($update->callbackQuery->data == CallbackData::CANCEL) {
            $this->dao->setDestination($update->callbackQuery->message->chat->id, Destinations::HOME);
        }
        $this->destinationController->index(Users::createFromData($this->dao->getUser($update->callbackQuery->message->chat->id)), $update);
    }

    /**
     * @param CallbackQueryTG $callbackQuery
     * @return void
     */
    public function onLanguageSelect(CallbackQueryTG $callbackQuery): void
    {
        if ($callbackQuery->data == CallbackData::LANGUAGE_RU) {
            $this->dao->setLanguage($callbackQuery->message->chat->id, Languages::RU);
        }
        if ($callbackQuery->data == CallbackData::LANGUAGE_UZ) {
            $this->dao->setLanguage($callbackQuery->message->chat->id, Languages::UZ);
        }
        if ($callbackQuery->data == CallbackData::LANGUAGE_EN) {
            $this->dao->setLanguage($callbackQuery->message->chat->id, Languages::EN);
        }
    }

    /**
     * @param CallbackQueryTG $callbackQuery
     * @return void
     */
    public function onHomeScreenSelect(CallbackQueryTG $callbackQuery): void
    {
        if ($callbackQuery->data == CallbackData::HOME_LANGUAGE) {
            $this->dao->setDestination($callbackQuery->message->chat->id, Destinations::LANGUAGE);
        }
        if ($callbackQuery->data == CallbackData::HOME_ANTICOR) {
            $this->dao->setDestination($callbackQuery->message->chat->id, Destinations::ANTICOR_NEW_RECORD);
        }
        if ($callbackQuery->data == CallbackData::HOME_MUROJAAT) {
            $this->dao->setDestination($callbackQuery->message->chat->id, Destinations::MUROJAAT_NEW_RECORD);
        }
    }

    /**
     * @param CallbackQueryTG $callbackQuery
     * @return void
     */
    public function onAdminSelected(CallbackQueryTG $callbackQuery): void
    {
        if ($callbackQuery->data == CallbackData::INCOME_ANTICOR) {
            $this->dao->setDestination($callbackQuery->message->chat->id, Destinations::ANTICOR_ADMIN);
        }
        if ($callbackQuery->data == CallbackData::INCOME_MUROJAAT) {
            $this->dao->setDestination($callbackQuery->message->chat->id, Destinations::MUROJAAT_ADMIN);
        }
    }
}
