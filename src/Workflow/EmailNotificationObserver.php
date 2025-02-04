<?php

namespace App\Workflow;

class EmailNotificationObserver implements Observer {
    public function update() {
        echo "🔔 Notification : Changement d'état du workflow.\n";
    }
}
