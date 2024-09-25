<?php

namespace App\Http\Controllers;

use App\Dao\MessagesDao;
use App\Dao\UsersDao;
use App\Models\Destinations;
use App\Models\MessageType;
use App\Models\UpdateTG;
use App\Models\Users;

class RecordController
{
    private UsersDao $usersDao;
    private MessagesDao $messagesDao;
    private AnticorController $anticorController;

    /**
     * @param UsersDao $usersDao
     * @param MessagesDao $messagesDao
     * @param AnticorController $anticorController
     */
    public function __construct(
        UsersDao          $usersDao,
        MessagesDao       $messagesDao,
        AnticorController $anticorController,
    )
    {
        $this->usersDao = $usersDao;
        $this->messagesDao = $messagesDao;
        $this->anticorController = $anticorController;
    }

    public function index(UpdateTG $update): void
    {
        if ($this->usersDao->inOnDestination($update->message->chat->id, Destinations::ANTICOR_NEW_RECORD)) {
            $user = Users::createFromData($this->usersDao->getUser($update->message->chat->id));
            // Store new record
            $this->messagesDao->createNewRecord($update, MessageType::ANTICOR);
            // Show success message
            $this->anticorController->newRecord($user);
        }
    }
}
