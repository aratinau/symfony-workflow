<?php

namespace App\EventListener;

use Symfony\Component\Workflow\Attribute\AsTransitionListener;
use Symfony\Component\Workflow\Event\TransitionEvent;

class ArticleWorkflowEventListener
{
    #[AsTransitionListener(workflow: 'my-workflow', transition: 'to_read')]
    public function onPublishedTransition(TransitionEvent $event): void
    {
        // ...
    }

    // ...
}
