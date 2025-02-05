<?php

namespace App\Workflow\Place;

use App\Workflow\StateContext;
use App\Workflow\Strategy\TransitionStrategy;

class DraftState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Brouillon à Soumis\n";

        $strategy->executeTransition($context, new SubmittedState());
    }
}
