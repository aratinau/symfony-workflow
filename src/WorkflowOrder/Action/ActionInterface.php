<?php

namespace App\WorkflowOrder\Action;

use App\Entity\OrderWorkflowPlace;

interface ActionInterface
{
    public function execute($order, OrderWorkflowPlace $workflowPlace);
}
