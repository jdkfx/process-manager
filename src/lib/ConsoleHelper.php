<?php

namespace Lib;

class ConsoleHelper
{
    public static function colorEcho(string $message, string $color = 'reset'): void
    {
        $colors = [
            'reset' => "\033[0m",
            'red' => "\033[31m",
            'green' => "\033[32m",
            'yellow' => "\033[33m",
            'blue' => "\033[34m",
            'magenta' => "\033[35m",
            'cyan' => "\033[36m",
        ];

        $code = $colors[$color] ?? $colors['reset'];
        echo $code . $message . $colors['reset'] . "\n";
    }
}