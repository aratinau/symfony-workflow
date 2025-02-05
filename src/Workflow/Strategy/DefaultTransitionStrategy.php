<?php

namespace App\Workflow\Strategy;

use App\Workflow\Place\WorkflowState;
use App\Workflow\StateContext;

class DefaultTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        $context->setState($nextState);
    }
}
