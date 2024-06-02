<?php

namespace App\FSM;

interface StateStorageInterface
{
    public function getInitialStateClass(): string;
    public function readState(): string | null;
    public function saveState(string $state): void;
}
