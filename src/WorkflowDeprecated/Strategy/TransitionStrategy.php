<?php

namespace App\WorkflowDeprecated\Strategy;

use App\WorkflowDeprecated\Place\WorkflowState;
use App\WorkflowDeprecated\StateContext;

interface TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState);
}
