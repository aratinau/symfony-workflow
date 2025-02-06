<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorkflowPlace;
use App\Repository\OrderWorkflowPlaceRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderTransition
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderWorkflowPlaceRepository $orderWorkflowPlaceRepository,
    ) {
    }

    public function getAllTransitions(int $workflow): array
    {
        $places = $this->orderWorkflowPlaceRepository->findPlacesByWorkflow($workflow);

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
