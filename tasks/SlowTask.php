<?php

namespace Tasks;

class SlowTask implements TaskInterface {
    public function getName(): string {
        return 'SlowTask';
    }

    public function execute(): void {
        echo "[{$this->getName()}] ...zzZ\n";
        sleep(20);
    }
}
