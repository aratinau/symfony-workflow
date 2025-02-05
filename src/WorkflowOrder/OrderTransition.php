<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorklowPlace;
use Doctrine\ORM\EntityManagerInterface;

class OrderTransition
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    // TODO
    // TODO - pouvoir configurer les boutons en front (mettre une couleur)
    // TODO - qui alerter
    // TODO - qui peut modifier
    // TODO - Quelle action sur l'objet
    // TODO - Quelle action sur l'objet en fonction d'autre objet (changer la catégorie ?)

    public function getAllTransitions(): array
    {
        $places = $this->entityManager
            ->getRepository(OrderWorklowPlace::class)
            ->findAll();

        $transitions = [];
        foreach ($places as $place) {
            $transitions[$place->getName()] = $this->getAllowedTransitions($place);
        }

        return $transitions;
    }

    public function canTransition(string $currentState, string $newState): bool
    {
        $transitions = $this->getAllTransitions();

        return in_array($newState, $transitions[$currentState] ?? [], true);
    }

    private function getAllowedTransitions(OrderWorklowPlace $place): array
    {
        $allowedTransitions = $place->getAllowedTransitions();

        return $allowedTransitions->map(fn($transition) => $transition->getName())->toArray();
    }

}
