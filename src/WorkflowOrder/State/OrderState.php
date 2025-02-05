<?php

namespace App\WorkflowOrder\State;

use App\Entity\Order;

interface OrderState
{
    public function process(Order $order): void;
}
