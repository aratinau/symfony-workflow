<?php

namespace App\Workflow;

use App\Entity\Workflow\PlaceDeprecated;
use App\Entity\Workflow\TransitionDeprecated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition as WorkflowTransition;
use Symfony\Component\Workflow\Workflow;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/***
 * @deprecated
 */
class DynamicWorkflowLoader
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function createDynamicWorkflow(string $target): Workflow
    {
        return $this->createWorkflowFromEntities($target);
    }

    private function createWorkflowFromEntities(string $target): Workflow
    {
        $places = $this->entityManager
            ->getRepository(PlaceDeprecated::class)
            ->findBy(['target' => $target]);

        $transitions = $this->entityManager
            ->getRepository(TransitionDeprecated::class)
            ->findBy(['target' => $target]);

        $definitionBuilder = new DefinitionBuilder();

        foreach ($places as $place) {
            $definitionBuilder->addPlace($place->getName());
        }

        foreach ($transitions as $transition) {
            $definitionBuilder->addTransition(
                new WorkflowTransition(
                    $transition->getName(),
                    $transition->getFromPlace()->getName(),
                    $transition->getToPlace()->getName()
                )
            );
        }

        $definition = $definitionBuilder->build();
        $marking = new MethodMarkingStore(true, 'publicStatus');

        return new Workflow(
            definition: $definition,
            markingStore: $marking,
            name: 'dynamic_workflow'
        );
    }
}
