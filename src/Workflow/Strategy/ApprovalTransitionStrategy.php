<?php

namespace App\Workflow\Strategy;

use App\Workflow\Place\WorkflowState;
use App\Workflow\StateContext;

class ApprovalTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        echo "Validation nécessaire avant approbation\n";
        $context->setState($nextState);
    }
}
