<?php

namespace App\Workflow;

interface TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState);
}
