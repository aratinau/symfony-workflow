<?php

namespace App\Workflow;

class WorkflowObserver {
    private static $instance;
    private $observers = [];

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new WorkflowObserver();
        }
        return self::$instance;
    }

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }
}
