<?php

namespace App\WorkflowOrder\Action;

use App\Entity\OrderWorkflowPlace;
use App\WorkflowOrder\WorkflowInterface;

interface ActionInterface
{
    public function execute(WorkflowInterface $entity, OrderWorkflowPlace $workflowPlace);
}
