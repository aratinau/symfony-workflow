<?php

namespace App\WorkflowOrder\State;

use App\Entity\OrderWorkflowPlace;
use App\WorkflowOrder\WorkflowInterface;

class State
{
    public function __construct(
        private OrderWorkflowPlace $orderWorkflowPlace,
//        private $transitions
    ) {
    }

    public function process(WorkflowInterface $entity): void
    {
        $entity->setCurrentState($this->orderWorkflowPlace);
    }

    public function getTransitions()
    {
        return $this->transitions;
    }
}
