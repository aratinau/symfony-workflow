<?php

namespace App\WorkflowOrder;

use App\Entity\Order;
use App\Entity\OrderWorklowPlace;
use App\Repository\OrderWorklowPlaceRepository;
use App\WorkflowOrder\State\DeliveredState;
use App\WorkflowOrder\State\PendingState;
use App\WorkflowOrder\State\ProcessingState;
use App\WorkflowOrder\State\ShippedState;
use App\WorkflowOrder\State\State;

class OrderWorkflow
{
    private array $states = [];

    public function __construct(
        private OrderTransition $orderTransition,
        private OrderWorklowPlaceRepository $orderWorklowPlaceRepository,
    ) {
        foreach ($this->orderTransition->getAllTransitions() as $name => $transition) {
            $this->states[$name] = new State($name, $transition);
        }
    }

    public function process(Order $order, string $nextState): void
    {
        $currentState = $order->getState();

        // Vérifier si l'état actuel existe
        if (!isset($this->states[$currentState])) {
            throw new \Exception("État actuel inconnu : $currentState");
        }

        // Vérifier si l'état suivant est valide
        if (!in_array($nextState, $this->states[$currentState]->getTransitions())) {
            throw new \Exception("Transition non autorisée : $currentState -> $nextState");
        }

        // TODO Mettre alerte ici s'il y en a
        $nextState = $this->orderWorklowPlaceRepository->findOneBy(['name' => $nextState]);

        // Mettre à jour l'état de la commande
        $this->states[$nextState->getName()]->process($order);
    }

    public function getAvailableTransitions(string $currentState): array
    {
        // Utilise OrderTransition pour obtenir les transitions dynamiques
        $allTransitions = $this->orderTransition->getAllTransitions();

        return $allTransitions[$currentState] ?? [];
    }
}
