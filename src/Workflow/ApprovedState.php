<?php

namespace App\Workflow;

class ApprovedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà approuvé.\n";
    }
}
