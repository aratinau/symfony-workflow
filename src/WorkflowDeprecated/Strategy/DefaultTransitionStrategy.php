<?php

namespace App\WorkflowDeprecated\Strategy;

use App\WorkflowDeprecated\Place\WorkflowState;
use App\WorkflowDeprecated\StateContext;

class DefaultTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        $context->setState($nextState);
    }
}
