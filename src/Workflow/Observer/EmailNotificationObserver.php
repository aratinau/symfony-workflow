<?php

namespace App\Workflow\Observer;

class EmailNotificationObserver implements Observer {
    public function update() {
        echo "🔔 Notification : Changement d'état du workflow.\n";
    }
}
