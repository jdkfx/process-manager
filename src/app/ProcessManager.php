<?php

namespace App;

use Lib\ConsoleHelper;

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
                ConsoleHelper::colorEcho("[PID " . getmypid() . "] Start: " . $task->getName(), 'blue');
                $task->execute();
                ConsoleHelper::colorEcho("[PID " . getmypid() . "] End: " . $task->getName(), 'green');
                exit();
            } else {
                $this->children[$pid] = true;
                $this->startTimes[$pid] = time();
            }
        }

        while (count($this->children) > 0) {
            $this->monitorProcesses();
            sleep(1);
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

    public function monitorProcesses(): void
    {
        foreach ($this->children as $pid => $_) {
            $elapsed = time() - $this->startTimes[$pid];
            if ($elapsed > $this->timeoutSeconds) {
                if (10 < $elapsed && $elapsed < 20) {
                    ConsoleHelper::colorEcho("[PID $pid] Timeout alert! Elapsed: {$elapsed} seconds", 'yellow');
                } else if (20 <= $elapsed && $elapsed < 30) {
                    ConsoleHelper::colorEcho("[PID $pid] Timeout alert! Elapsed: {$elapsed} seconds", 'magenta');
                } else if (30 <= $elapsed) {
                    ConsoleHelper::colorEcho("[PID $pid] HANG suspected! Killing process...", 'red');
                    posix_kill($pid, SIGKILL);
                    unset($this->children[$pid]);
                    unset($this->startTimes[$pid]);
                }
            }
        }

        while (($pid = pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
            unset($this->children[$pid]);
            unset($this->startTimes[$pid]);
        }
    }
}