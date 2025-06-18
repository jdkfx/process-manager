<?php

namespace App;

use Tasks\TaskInterface;

class ProcessManager
{
    public int $maxProcesses;
    public array $children = [];

    public function __construct(int $maxProcesses = 3)
    {
        $this->maxProcesses = $maxProcesses;
    }

    public function run(array $taskInstances): void
    {
        foreach ($taskInstances as $task) {
            while (count($this->children) >= $this->maxProcesses) {
                $this->waitForChild();
            }

            $pid = pcntl_fork();

            if ($pid === -1) {
                die("could not fork\n");
            } else if ($pid === 0) {
                echo "[PID " . getmypid() . "] Start: " . $task->getName() . "\n";
                $task->execute();
                echo "[PID " . getmypid() . "] End: " . $task->getName() . "\n";
                exit();
            } else {
                $this->children[$pid] = true;
            }
        }

        while (count($this->children) > 0) {
            $this->waitForChild();
        }
    }

    public function waitForChild(): void
    {
        $pid = pcntl_wait($status);
        if ($pid > 0) {
            unset($this->children[$pid]);
        }
    }
}