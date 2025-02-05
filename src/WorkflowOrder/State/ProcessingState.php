<?php

namespace App\WorkflowOrder\State;

use App\Entity\Order;
use App\WorkflowOrder\OrderTransition;

class ProcessingState implements OrderState
{
    public function process(Order $order): void
    {
        if (OrderTransition::canTransition($order->getState(), 'shipped')) {
            $order->setState('shipped');
        } else {
            throw new \Exception('Transition non autorisée.');
        }
    }
}
