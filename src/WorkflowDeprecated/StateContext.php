<?php

namespace App\WorkflowDeprecated;

use App\WorkflowDeprecated\Observer\WorkflowObserver;
use App\WorkflowDeprecated\Place\WorkflowState;
use App\WorkflowDeprecated\Strategy\TransitionStrategy;

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
