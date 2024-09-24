<?php

namespace App\FSM;

use ReflectionClass;

interface IStateModel
{
    public function getAlias(): string;

    public static function states(): array;

    public function initialState(): ReflectionClass;

    public function currentState(): ReflectionClass;

    public function updateState(ReflectionClass|null $state): void;

}
