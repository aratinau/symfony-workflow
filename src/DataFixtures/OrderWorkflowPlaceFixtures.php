<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderWorkflow;
use App\Entity\OrderWorkflowPlace;
use App\Entity\OrderWorkflowTransition;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderWorkflowPlaceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Définir les workflows avec leurs états et transitions
        $workflows = [/*
            'Commande Workflow Standard' => [
                'pending' => ['processing', 'cancelled'],
                'processing' => ['shipped', 'cancelled'],
                'shipped' => ['delivered'],
                'delivered' => [],
                'cancelled' => [],
            ],
            'Commande Workflow Express' => [
                'pending' => ['processing', 'cancelled'],
                'processing' => ['shipped'],
                'shipped' => ['delivered'],
                'delivered' => [],
                'cancelled' => [],
            ],*/
            'Commande Workflow Complexe' => [
                'pending' => ['processing', 'cancelled', 'on_hold'],
                'processing' => ['shipped', 'cancelled', 'on_hold'],
                'shipped' => ['delivered', 'returned'],
                'delivered' => ['returned'],
                'on_hold' => ['processing', 'cancelled'],
                'returned' => [],
                'cancelled' => [],
            ],
        ];

        foreach ($workflows as $workflowName => $states) {
            // Créer un nouveau workflow
            $orderWorkflow = new OrderWorkflow();
            $orderWorkflow->setName($workflowName);

            // Créer les états et les transitions
            $stateInstances = $this->createStatesAndTransitions($manager, $orderWorkflow, $states);

            // Sauvegarder le workflow
            $manager->persist($orderWorkflow);

            // Ajouter le workflow à la liste des instances
            $workflowInstances[] = $orderWorkflow;
        }

        // Créer des commandes et les associer à un workflow et un état initial
        for ($i = 1; $i <= 10; $i++) {
            $order = new Order();
            $order->setUpdatedAt(new \DateTime());
            $order->setCreatedAt(new \DateTime());
            $order->setName("Commande $i");
            $order->setAmount(rand(100, 1000));

            // Associer un workflow aléatoire
            $randomWorkflow = $workflowInstances[array_rand($workflowInstances)];
            $order->setCurrentWorkflow($randomWorkflow);

            // Associer un état initial (par exemple, 'pending')
//            $initialState = $randomWorkflow->getOrderWorkflowPlaces()->filter(function (OrderWorkflowPlace $place) {
//                return $place->getName() === 'pending';
//            })->first();
            $order->setCurrentState($stateInstances['pending']);

            $manager->persist($order);
        }

        // Enregistrer toutes les modifications dans la base de données
        $manager->flush();
    }

    /**
     * Crée les états et les transitions pour un workflow donné.
     *
     * @param ObjectManager $manager
     * @param OrderWorkflow $workflow
     * @param array $states
     * @return array
     */
    private function createStatesAndTransitions(ObjectManager $manager, OrderWorkflow $workflow, array $states): array
    {
        $stateInstances = [];

        // Créer les états
        foreach ($states as $name => $allowedTransitions) {
            $state = new OrderWorkflowPlace();
            $state->setName($name);
            $state->setWorkflow($workflow); // Associer l'état au workflow

            // Ajouter l'état au tableau temporaire
            $stateInstances[$name] = $state;

            // Sauvegarder l'état dans la base de données
            $manager->persist($state);
        }

        // Créer les transitions
        foreach ($states as $name => $allowedTransitions) {
            $fromState = $stateInstances[$name];

            foreach ($allowedTransitions as $transitionName) {
                if (isset($stateInstances[$transitionName])) {
                    $toState = $stateInstances[$transitionName];

                    // Créer une nouvelle transition
                    $transition = new OrderWorkflowTransition();
                    $transition->setName("Transition from $name to $transitionName");
                    $transition->setFromPlace($fromState);
                    $transition->setToPlace($toState);

                    // Ajouter la transition à l'état de départ
                    $fromState->addOutgoingTransition($transition);

                    // Sauvegarder la transition
                    $manager->persist($transition);
                }
            }

            // Mettre à jour l'état avec ses transitions sortantes
            $manager->persist($fromState);
        }

        return $stateInstances;
    }
}
