<?php

namespace App\DataFixtures;

use App\Entity\OrderWorkflow;
use App\Entity\OrderWorkflowPlace;
use App\Factory\OrderWorkflowFactory;
use App\Factory\OrderWorkflowPlaceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderWorkflowPlaceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Définir les états et leurs transitions autorisées
        $states = [
            'pending' => ['processing', 'shipped'],
            'processing' => ['shipped', 'delivered'],
            'shipped' => ['delivered'],
            'delivered' => ['sign'],
            'sign' => [], // Aucune transition depuis "sign"
        ];

        // Créer un tableau pour stocker les instances d'états
        $stateInstances = [];

        // Créer un OrderWorkflow
        $orderWorkflow = new OrderWorkflow();
        $orderWorkflow->setName('Commande Workflow');

        // Instancier chaque état et l'associer au workflow
        foreach ($states as $name => $allowedTransitions) {
            $state = new OrderWorkflowPlace();
            $state->setName($name);
            $state->setWorkflow($orderWorkflow); // Associer l'état au workflow

            // Ajouter l'état au tableau temporaire
            $stateInstances[$name] = $state;

            // Sauvegarder l'état dans la base de données
            $manager->persist($state);
        }

        // Définir les transitions autorisées pour chaque état
        foreach ($states as $name => $allowedTransitions) {
            $state = $stateInstances[$name];

            foreach ($allowedTransitions as $transitionName) {
                if (isset($stateInstances[$transitionName])) {
                    $state->addAllowedTransition($stateInstances[$transitionName]);
                }
            }

            // Mettre à jour l'état avec ses transitions autorisées
            $manager->persist($state);
        }

        // Sauvegarder le workflow dans la base de données
        $manager->persist($orderWorkflow);

        // Enregistrer toutes les modifications dans la base de données
        $manager->flush();
    }
}
