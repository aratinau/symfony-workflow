<?php

namespace App\WorkflowOrder\State;

use App\Entity\Order;
use App\WorkflowOrder\OrderTransition;

class ShippedState implements OrderState
{
    public function process(Order $order): void
    {
        if (OrderTransition::canTransition($order->getState(), 'delivered')) {
            $order->setState('delivered');
        } else {
            throw new \Exception('Transition non autorisée.');
        }
    }
}
