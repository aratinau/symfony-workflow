<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorkflow;
use App\Entity\OrderWorkflowPlace;

interface WorkflowInterface
{
    public function setCurrentWorkflow(?OrderWorkflow $currentWorkflow): static;
    public function getCurrentWorkflow(): ?OrderWorkflow;

    public function setCurrentState(?OrderWorkflowPlace $currentState): static;
    public function getCurrentState(): ?OrderWorkflowPlace;
}
