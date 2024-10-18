<?php

namespace App\EventSubscriber;

use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class PublicStatusWorkflowSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.public_status.transition.to_read' => 'onSentToRead',
        ];
    }

    public function onSentToRead(Event $event)
    {
        $subject = $event->getSubject();
        $this->logger->info(sprintf(
            'Transition from SENT to READ executed for item with ID %d',
            $subject->getId()
        ));

        // Vous pouvez également ajouter d'autres actions ici, comme envoyer un e-mail, notifier un utilisateur, etc.
    }
}
