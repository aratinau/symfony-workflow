<?php

namespace App\Workflow;

use App\Workflow\Place\ApprovedState;
use App\Workflow\Place\DraftState;
use App\Workflow\Place\RejectedState;
use App\Workflow\Place\SubmittedState;
use App\Workflow\Strategy\ApprovalTransitionStrategy;
use App\Workflow\Strategy\DefaultTransitionStrategy;
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
