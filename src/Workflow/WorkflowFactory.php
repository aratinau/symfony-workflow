<?php

namespace App\Workflow;

use App\Repository\WorkflowRepository;
use App\Repository\WorkflowPlaceRepository;
use App\Repository\WorkflowTransitionRepository;
use Exception;

class WorkflowFactory
{
    public function __construct(
        private WorkflowRepository           $repository,
        private WorkflowPlaceRepository      $workflowPlaceRepository,
        private WorkflowTransitionRepository $workflowTransitionRepository
    ) {
//        $this->repository = $repository;
//        $this->states = $this->repository->getStates();
//        $this->transitions = $this->repository->getTransitions($this->states);
    }

    public function createState(string $stateName): WorkflowState {
        if (!isset($this->states[$stateName])) {
            throw new Exception("État introuvable : $stateName");
        }
        return $this->states[$stateName];
    }

    public function getTransitionsForState(string $stateName): array {
        return array_filter($this->transitions, function ($transition) use ($stateName) {
            return $transition->fromState->name === $stateName;
        });
    }

    public function createStrategy(string $strategyType): TransitionStrategy {
        return match ($strategyType) {
            'default' => new DefaultTransitionStrategy(),
            'approval' => new ApprovalTransitionStrategy(),
            default => throw new Exception("Stratégie inconnue : $strategyType"),
        };
    }

    /*
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
    */
}
