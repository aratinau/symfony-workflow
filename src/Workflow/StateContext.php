<?php

namespace App\Workflow;

class StateContext {
    private $currentState;

    public function __construct(WorkflowState $state) {
        $this->currentState = $state;
    }

    public function setState(WorkflowState $state) {
        $this->currentState = $state;
        WorkflowObserver::getInstance()->notify();
    }

    public function proceed(TransitionStrategy $strategy) {
        $this->currentState->proceedToNext($this, $strategy);
    }
}
