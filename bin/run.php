#!/usr/bin/env php
<?php

require_once __DIR__ . '/../tasks/TaskInterface.php';
require_once __DIR__ . '/../tasks/HelloTask.php';

$taskClasses = require __DIR__ . '/../config/tasks.php';

foreach ($taskClasses as $taskClass) {
    $task = new $taskClass();
    echo "=== Running: {$task->getName()} ===\n";
    $task->execute();
    echo "=== Finished: {$task->getName()} ===\n\n";
}