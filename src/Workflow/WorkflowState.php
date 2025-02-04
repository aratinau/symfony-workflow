<?php

namespace App\Workflow;

interface WorkflowState
{
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy);
}
