<?php

namespace App\DataFixtures;

use App\Factory\BaseItemFactory;
use App\Factory\OrderFactory;
use App\Factory\OrderWorkflowFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createSequence([
            [
                'email' => 'elise60@noos.fr',
                'firstName' => 'Elise',
                'lastName' => 'Noos',
            ],
            [
                'email' => 'bsmith@smith.fr',
                'firstName' => 'Bertrand',
                'lastName' => 'Smith',
            ],
        ]);


        UserFactory::createMany(50);
        BaseItemFactory::createMany(50);
//        OrderFactory::createMany(50);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ServiceFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
