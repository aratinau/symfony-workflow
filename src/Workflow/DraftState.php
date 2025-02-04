<?php

namespace App\Workflow;

class DraftState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Brouillon à Soumis\n";
        $strategy->executeTransition($context, new SubmittedState());
    }
}
