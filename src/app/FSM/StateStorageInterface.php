<?php

namespace App\FSM;

interface StateStorageInterface
{
    public function getInitialStateDashedName(): string;
    public function getState(): string;
    public function setState(string $state): void;
}
