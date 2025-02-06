<?php

namespace App\WorkflowDeprecated\Place;

use App\WorkflowDeprecated\StateContext;
use App\WorkflowDeprecated\Strategy\TransitionStrategy;

interface WorkflowState
{
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy);
}
