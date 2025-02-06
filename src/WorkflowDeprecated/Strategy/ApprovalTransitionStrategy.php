<?php

namespace App\WorkflowDeprecated\Strategy;

use App\WorkflowDeprecated\Place\WorkflowState;
use App\WorkflowDeprecated\StateContext;

class ApprovalTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        echo "Validation nécessaire avant approbation\n";
        $context->setState($nextState);
    }
}
