<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorkflow;
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
            $transitions[$place->getId()] = $this->getAllowedTransitions($place);
        }

        return $transitions;
    }

    private function getAllowedTransitions(OrderWorkflowPlace $place)
    {
        $allowedTransitions = $place->getOutgoingTransitions();

        return $allowedTransitions->map(fn($transition) => $transition->getId());
    }

    public function getTransitions(OrderWorkflow $workflow): array
    {
        $transitions = [];

        foreach ($workflow->getOrderWorkflowPlaces() as $place) {
            foreach ($place->getOutgoingTransitions() as $nextPlace) {
                $transitions[] = [
                    $place->getName(),       // État de départ
                    $nextPlace->getName(),   // État d'arrivée
                    // TODO $place->getTransitionName(), // Nom de la transition
                    'transition name'
                ];
            }
        }

        return $transitions;
    }

    public function getAvailableTransitions(OrderWorkflow $workflow, OrderWorkflowPlace $currentState)
    {
        return $this->orderWorkflowPlaceRepository->findBy([
            'workflow' => $workflow,
        ]);
    }
}
