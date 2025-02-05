<?php

namespace App\WorkflowOrder\Action;

use App\Entity\OrderWorkflowPlace;
use App\Entity\WorkflowPlace;

class SendNotificationAction implements ActionInterface
{
    public function execute($order, OrderWorkflowPlace $workflowPlace)
    {
        dd('todo notif');
        if (!isset($actions['recipient']) || !isset($actions['message'])) {
            throw new \InvalidArgumentException("Les clés 'recipient' et 'message' sont requises pour l'action 'send_notification'.");
        }

        // Récupérer les détails de la notification depuis le contexte
        $recipient = $actions['recipient'];
        $message = $actions['message'];

        // Envoyer la notification
        $this->sendNotification($recipient, $message);

        // Log
        // $this->logger->info("Notification envoyée à {$recipient} avec le message '{$message}'");
    }

    private function sendNotification($recipient, $message)
    {
        // Logique pour envoyer la notification
    }
}
