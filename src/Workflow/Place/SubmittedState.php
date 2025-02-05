<?php

namespace App\Workflow\Place;

use App\Workflow\StateContext;
use App\Workflow\Strategy\TransitionStrategy;

class SubmittedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Soumis à Approuvé ou Rejeté\n";

        $strategy->executeTransition($context, new ApprovedState());
    }
}
