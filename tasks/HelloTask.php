<?php

namespace Tasks;

class HelloTask implements TaskInterface {
    public function getName(): string {
        return 'HelloTask';
    }

    public function execute(): void {
        echo "[{$this->getName()}] Hello from the task!\n";
        sleep(1);
    }
}