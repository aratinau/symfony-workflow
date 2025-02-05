<?php

namespace App\Workflow;

use App\Workflow\Observer\WorkflowObserver;
use App\Workflow\Place\WorkflowState;
use App\Workflow\Strategy\TransitionStrategy;

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

    public function getCurrentState() {
        return $this->currentState;
    }
}
