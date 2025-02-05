<?php

namespace App\WorkflowOrder\State;


use App\Entity\Order;

class DeliveredState implements OrderState
{
    public function process(Order $order): void
    {
        // État final, aucune transition possible
        throw new \Exception('Impossible de changer l’état après livraison.');
    }
}
