<?php

namespace App\DataFixtures;

use App\Entity\OrderWorkflow;
use App\Entity\OrderWorkflowPlace;
use App\Factory\OrderWorkflowFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderWorkflowFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        OrderWorkflowFactory::createOne(['name' => 'Workflow Principal']);
    }
}
