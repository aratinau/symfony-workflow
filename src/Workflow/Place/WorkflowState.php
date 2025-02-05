<?php

namespace App\Workflow\Place;

use App\Workflow\StateContext;
use App\Workflow\Strategy\TransitionStrategy;

interface WorkflowState
{
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy);
}
