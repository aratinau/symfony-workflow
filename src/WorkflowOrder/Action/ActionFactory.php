<?php

namespace App\WorkflowOrder\Action;

class ActionFactory
{
    public function __construct(
        private ChangeCategoryAction $changeCategoryAction,
        private SendNotificationAction $sendNotificationAction,
        private ChangeOwnerAction $changeOwnerAction,
    ) {
    }

    public function create($actionType)
    {
        switch ($actionType) {
            case 'change_category':
                return $this->changeCategoryAction;
            case 'send_notification':
                return $this->sendNotificationAction;
            case 'change_owner':
                return $this->changeOwnerAction;
            default:

                throw new \Exception("Type d'action inconnu : {$actionType}");
        }
    }
}
