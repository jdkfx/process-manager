<?php

namespace App;

class ProcessManager
{
    public int $maxProcesses;
    public array $children = [];
    public array $startTimes = [];
    public int $timeoutSeconds = 10;

    public function __construct(int $maxProcesses = 3, int $timeoutSeconds = 10)
    {
        $this->maxProcesses = $maxProcesses;
        $this->timeoutSeconds = $timeoutSeconds;
    }

    public function run(array $taskInstances): void
    {
        foreach ($taskInstances as $task) {
            while (count($this->children) >= $this->maxProcesses) {
                $this->waitForChild();
                $this->checkTimeouts();
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
                $this->startTimes[$pid] = time();
            }
        }

        while (count($this->children) > 0) {
            $this->waitForChild();
            $this->checkTimeouts();
        }
    }

    public function waitForChild(): void
    {
        $pid = pcntl_wait($status, WNOHANG);
        if ($pid > 0) {
            unset($this->children[$pid]);
            unset($this->startTimes[$pid]);
        } else {
            sleep(1);
        }
    }

    public function checkTimeouts(): void
    {
        $now = time();
        foreach ($this->startTimes as $pid => $start) {
            $duration = $now - $start;
            if ($duration > $this->timeoutSeconds) {
                fwrite(STDERR, "[PID $pid] has elapsed for more than $duration seconds!\n");
            }
        }
    }
}