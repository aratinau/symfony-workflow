<?php

namespace App\Workflow;

class SubmittedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Soumis à Approuvé ou Rejeté\n";
        $strategy->executeTransition($context, new ApprovedState());
    }
}
