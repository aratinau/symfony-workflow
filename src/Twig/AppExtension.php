<?php

namespace App\Twig;

use App\WorkflowOrder\OrderWorkflow;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $workflow;

    public function __construct(OrderWorkflow $workflow)
    {
        $this->workflow = $workflow;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_available_transitions', [$this, 'getAvailableTransitions']),
        ];
    }

    public function getAvailableTransitions($workflow, $state)
    {
        return $this->workflow->getAvailableTransitions($workflow, $state);
    }
}
