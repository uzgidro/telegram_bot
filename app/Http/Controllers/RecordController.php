<?php

namespace App\Http\Controllers;

use App\Constants\Destinations;
use App\Constants\MessageType;
use App\Dao\MessagesDao;
use App\Dao\UsersDao;
use App\Models\UpdateTG;
use App\Models\Users;

class RecordController
{
    private UsersDao $usersDao;
    private MessagesDao $messagesDao;
    private AnticorController $anticorController;
    private MurojaatController $murojaatController;

    /**
     * @param UsersDao $usersDao
     * @param MessagesDao $messagesDao
     * @param AnticorController $anticorController
     * @param MurojaatController $murojaatController
     */
    public function __construct(
        UsersDao          $usersDao,
        MessagesDao       $messagesDao,
        AnticorController $anticorController,
        MurojaatController $murojaatController
    )
    {
        $this->usersDao = $usersDao;
        $this->messagesDao = $messagesDao;
        $this->anticorController = $anticorController;
        $this->murojaatController = $murojaatController;
    }

    /**
     * @param UpdateTG $update
     * @return void
     */
    public function index(UpdateTG $update): void
    {
        if ($this->usersDao->inOnDestination($update->message->chat->id, Destinations::ANTICOR_NEW_RECORD)) {
            $user = Users::createFromData($this->usersDao->getUser($update->message->chat->id));
            // Store new record
            $this->messagesDao->createNewRecord($update, MessageType::ANTICOR);
            // Show success message
            $this->anticorController->newRecord($user);
        } elseif ($this->usersDao->inOnDestination($update->message->chat->id, Destinations::MUROJAAT_NEW_RECORD)) {
            $user = Users::createFromData($this->usersDao->getUser($update->message->chat->id));
            // Store new record
            $this->messagesDao->createNewRecord($update, MessageType::MUROJAAT);
            // Show success message
            $this->murojaatController->newRecord($user);
        }
    }
}
