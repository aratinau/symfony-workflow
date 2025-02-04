<?php

namespace App\Workflow;

class RejectedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà rejeté.\n";
    }
}
