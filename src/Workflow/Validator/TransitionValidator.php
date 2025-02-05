<?php

namespace App\Workflow\Validator;

abstract class TransitionValidator {
    protected $next;

    public function setNext(TransitionValidator $validator) {
        $this->next = $validator;
    }

    public function validate($request) {
        if ($this->next) {
            return $this->next->validate($request);
        }
        return true;
    }
}
