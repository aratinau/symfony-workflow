<?php

namespace App\WorkflowDeprecated;

use App\WorkflowDeprecated\Place\ApprovedState;
use App\WorkflowDeprecated\Place\DraftState;
use App\WorkflowDeprecated\Place\RejectedState;
use App\WorkflowDeprecated\Place\SubmittedState;
use App\WorkflowDeprecated\Strategy\ApprovalTransitionStrategy;
use App\WorkflowDeprecated\Strategy\DefaultTransitionStrategy;
use Exception;

class WorkflowFactory
{
    public static function createState($state) {
        switch ($state) {
            case 'draft': return new DraftState();
            case 'submitted': return new SubmittedState();
            case 'approved': return new ApprovedState();
            case 'rejected': return new RejectedState();
            default: throw new Exception("État inconnu");
        }
    }

    public static function createStrategy($type) {
        switch ($type) {
            case 'default': return new DefaultTransitionStrategy();
            case 'approval': return new ApprovalTransitionStrategy();
            default: throw new Exception("Stratégie inconnue");
        }
    }
}
