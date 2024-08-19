<?php

namespace App\DataFixtures;

use App\Factory\BaseItemFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(50);
        BaseItemFactory::createMany(50);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ServiceFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
