<?php

namespace App\WorkflowOrder\State;

use App\Entity\Order;
use App\WorkflowOrder\OrderTransition;

class PendingState implements OrderState
{
    public function process(Order $order): void
    {
        if (OrderTransition::canTransition($order->getState(), 'processing')) {
            $order->setState('processing');
        } else {
            throw new \Exception('Transition non autorisée.');
        }
    }
}
