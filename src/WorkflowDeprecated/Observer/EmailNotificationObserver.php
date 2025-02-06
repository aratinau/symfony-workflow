<?php

namespace App\WorkflowDeprecated\Observer;

class EmailNotificationObserver implements Observer {
    public function update() {
        echo "🔔 Notification : Changement d'état du workflow.\n";
    }
}
