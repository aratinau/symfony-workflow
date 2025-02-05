<?php

namespace App\Workflow\Place;

use App\Workflow\StateContext;
use App\Workflow\Strategy\TransitionStrategy;

class RejectedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà rejeté.\n";
    }
}
