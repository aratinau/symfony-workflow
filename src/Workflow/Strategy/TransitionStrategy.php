<?php

namespace App\Workflow\Strategy;

use App\Workflow\Place\WorkflowState;
use App\Workflow\StateContext;

interface TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState);
}
