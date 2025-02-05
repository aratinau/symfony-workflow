<?php

namespace App\DataFixtures;

use App\Entity\OrderWorklowPlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderWorklowPlaceFixtures extends Fixture
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

        // Instancier chaque état
        foreach ($states as $name => $allowedTransitions) {
            $state = new OrderWorklowPlace();
            $state->setName($name);

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

        // Enregistrer toutes les modifications dans la base de données
        $manager->flush();
    }
}
