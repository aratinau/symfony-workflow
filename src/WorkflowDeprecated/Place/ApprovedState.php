<?php

namespace App\WorkflowDeprecated\Place;

use App\WorkflowDeprecated\StateContext;
use App\WorkflowDeprecated\Strategy\TransitionStrategy;

class ApprovedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà approuvé.\n";
    }
}
