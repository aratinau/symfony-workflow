<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorkflowPlace;
use Doctrine\ORM\EntityManagerInterface;

class OrderTransition
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getAllTransitions(): array
    {
        $places = $this->entityManager
            ->getRepository(OrderWorkflowPlace::class)
            ->findAll();

        $transitions = [];
        foreach ($places as $place) {
            $transitions[$place->getName()] = $this->getAllowedTransitions($place);
        }

        return $transitions;
    }

    private function getAllowedTransitions(OrderWorkflowPlace $place): array
    {
        $allowedTransitions = $place->getAllowedTransitions();

        return $allowedTransitions->map(fn($transition) => $transition->getName())->toArray();
    }

}
