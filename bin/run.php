#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\ProcessManager;

$taskClasses = require __DIR__ . '/../config/tasks.php';

$taskInstances = array_map(function ($className) {
    return new $className();
}, $taskClasses);

$manager = new ProcessManager(maxProcesses: 3);
$manager->run($taskInstances);
