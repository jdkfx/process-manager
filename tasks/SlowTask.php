<?php

namespace Tasks;

use Lib\ConsoleHelper;

class SlowTask implements TaskInterface {
    public function getName(): string {
        return 'SlowTask';
    }

    public function execute(): void {
        ConsoleHelper::colorEcho("[{$this->getName()}] ...zzZ", 'cyan');
        sleep(50);
    }
}
