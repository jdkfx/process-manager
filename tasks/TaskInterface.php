<?php

namespace Tasks;

interface TaskInterface {
    public function getName(): string;
    public function execute(): void;
}