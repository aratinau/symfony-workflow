<?php

namespace App\WorkflowDeprecated\Place;

use App\WorkflowDeprecated\StateContext;
use App\WorkflowDeprecated\Strategy\TransitionStrategy;

class SubmittedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Soumis à Approuvé ou Rejeté\n";

        $strategy->executeTransition($context, new ApprovedState());
    }
}
