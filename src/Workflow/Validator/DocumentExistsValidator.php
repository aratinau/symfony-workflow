<?php

namespace App\Workflow\Validator;

class DocumentExistsValidator extends TransitionValidator {
    public function validate($request) {
        if (!$request['documentExists']) {
            echo "Erreur : Le document n'existe pas.\n";
            return false;
        }
        return parent::validate($request);
    }
}
