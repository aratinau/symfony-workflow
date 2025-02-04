<?php

namespace App\Workflow;

class ApprovalTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        echo "Validation nécessaire avant approbation\n";
        $context->setState($nextState);
    }
}
