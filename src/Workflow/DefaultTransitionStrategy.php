<?php

namespace App\Workflow;

class DefaultTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        $context->setState($nextState);
    }
}
