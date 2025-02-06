<?php

namespace App\Twig;

use App\WorkflowOrder\OrderTransition;
use App\WorkflowOrder\OrderWorkflowService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private OrderTransition $orderTransition)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_available_transitions', [$this, 'getAvailableTransitions']),
        ];
    }

    public function getAvailableTransitions($workflow, $state)
    {
        return $this->orderTransition->getAvailableTransitions($workflow, $state);
    }
}
