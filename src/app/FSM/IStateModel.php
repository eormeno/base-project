<?php

namespace App\FSM;

use ReflectionClass;
use Illuminate\Support\Carbon;

interface IStateModel
{
    public function getId(): int;

    public function getAlias(): string;

    public static function states(): array;

    // public function _getState(): string|null;

    public function initialState(): ReflectionClass;

    public function currentState(): ReflectionClass;

    public function updateState(ReflectionClass|null $state): void;

    // public function getEnteredAt(): string|null;

    // public function setEnteredAt(Carbon|string|null $enteredAt): void;

}
