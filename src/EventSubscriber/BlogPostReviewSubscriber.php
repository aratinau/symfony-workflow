<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class BlogPostReviewSubscriber implements EventSubscriberInterface
{
    public function guardReview(GuardEvent $event): void
    {
        /*$post = $event->getSubject();
        $title = $post->title;

        if (empty($title)) {
            $event->setBlocked(
                true,
                'This blog post cannot be marked as reviewed because it has no title.'
            );
        }*/
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.public_status.guard.to_read' => ['guardReview'],
        ];
    }
}
