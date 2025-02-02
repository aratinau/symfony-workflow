<?php

namespace App\EventSubscriber;

use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\Event\LeaveEvent;

class WorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.announce' => 'announce',
            'workflow.public_status.transition' => 'onTransition',
            'workflow.public_status.enter' => 'onEnterState',
            // LeaveEvent::getName('public_status') => 'onLeave',
        ];
    }

    public function announce(Event $event)
    {

    }

    public function onTransition(Event $event)
    {
        $subject = $event->getSubject();
        $transition = $event->getTransition();

        $this->logger->info(sprintf(
            'Transition "%s" executed for item with ID %d',
            $transition->getName(),
            $subject->getId()
        ));
    }

    public function onEnterState(Event $event)
    {
        $subject = $event->getSubject();
        $marking = $event->getMarking();

        $this->logger->info(sprintf(
            'Item with ID %d entered state(s): %s',
            $subject->getId(),
            implode(', ', array_keys($marking->getPlaces()))
        ));
    }
}
