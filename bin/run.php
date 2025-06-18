#!/usr/bin/env php
<?php

require_once __DIR__ . '/../tasks/TaskInterface.php';
require_once __DIR__ . '/../tasks/HelloTask.php';
require_once __DIR__ . '/../tasks/SlowTask.php';
require_once __DIR__ . '/../src/ProcessManager.php';

use App\ProcessManager;

$taskClasses = require __DIR__ . '/../config/tasks.php';

$taskInstances = [];
foreach ($taskClasses as $taskClass) {
    $taskInstances[] = new $taskClass();
}

$manager = new ProcessManager(3);
$manager->run($taskInstances);
