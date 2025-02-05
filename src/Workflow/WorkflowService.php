<?php

namespace App\Workflow;


class WorkflowService
{
    private StateContext $workflow;

    public function __construct()
    {
        $this->workflow = new StateContext(WorkflowFactory::createState('draft'));
    }

    public function getWorkflow(): StateContext
    {
        return $this->workflow;
    }
}
