<?php

namespace App\Workflow;

class UserHasPermissionValidator extends TransitionValidator {
    public function validate($request) {
        if (!$request['userHasPermission']) {
            echo "Erreur : L'utilisateur n'a pas les droits.\n";
            return false;
        }
        return parent::validate($request);
    }
}
