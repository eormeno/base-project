<?php

namespace App\FSM;

use ReflectionClass;

interface StateStorageInterface
{
    public function getInitialStateClass(): ReflectionClass;
    public function readState(): ReflectionClass | null;
    public function saveState(string | null $state): void;
}
