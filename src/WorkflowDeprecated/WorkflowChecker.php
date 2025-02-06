<?php

namespace App\WorkflowDeprecated;

use App\Entity\BaseItem;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class WorkflowChecker
{
    private const TRANSITIONS = [
        'new' => ['processing'],
        'processing' => ['shipped', 'canceled'],
        'shipped' => ['delivered'],
        'canceled' => [],
        'delivered' => []
    ];

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function applyTransition(BaseItem $order, string $newStatus): void
    {
        $currentStatus = $order->getPublicStatus();

        if (!isset(self::TRANSITIONS[$currentStatus])) {
            throw new InvalidArgumentException("Unknown status: $currentStatus");
        }

        if (!in_array($newStatus, self::TRANSITIONS[$currentStatus], true)) {
            throw new InvalidArgumentException("Invalid transition from $currentStatus to $newStatus");
        }

        $order->setPublicStatus($newStatus);

        $this->entityManager->flush();
    }
}
