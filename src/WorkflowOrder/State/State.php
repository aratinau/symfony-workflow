<?php

namespace App\WorkflowOrder\State;

use App\Entity\Order;

class State
{
    private string $name;
    private array $transitions;

    public function __construct(string $name, array $transitions)
    {
        $this->name = $name;
        $this->transitions = $transitions;
    }

    public function process(Order $order): void
    {
        $order->setState($this->name);
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }
}
