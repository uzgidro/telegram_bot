<?php

namespace App\Http\Controllers;

use App\Models\UpdateTG;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public CommandController $commandController;
    public CallbackController $callbackController;
    public RecordController $recordController;

    /**
     * @param CommandController $commandController
     * @param CallbackController $callbackController
     * @param RecordController $recordController
     */
    public function __construct(
        CommandController  $commandController,
        CallbackController $callbackController,
        RecordController $recordController
    )
    {
        $this->commandController = $commandController;
        $this->callbackController = $callbackController;
        $this->recordController = $recordController;
    }


    /**
     * @param Request $request
     * @return void
     */
    public function parseUpdateRequest(Request $request): void
    {
        $model = new UpdateTG($request->all());

        if (isset($model->message->entities->type) && $model->message->entities->type == 'bot_command') {
            $this->commandController->index($model);
        }
        elseif (isset($model->callbackQuery->data)) {
            $this->callbackController->index($model);
        }
        else {
            $this->recordController->index($model);
        }
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
        elseif (isset($model->callbackQuery->data)) {
            $this->callbackController->index($model);
        }
        else {
            $this->recordController->index($model);
        }
    }

}
