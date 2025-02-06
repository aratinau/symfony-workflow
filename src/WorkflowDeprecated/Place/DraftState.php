<?php

namespace App\WorkflowDeprecated\Place;

use App\WorkflowDeprecated\StateContext;
use App\WorkflowDeprecated\Strategy\TransitionStrategy;

class DraftState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Brouillon à Soumis\n";

        $strategy->executeTransition($context, new SubmittedState());
    }
}
