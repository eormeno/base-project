<?php

namespace App\FSM;

use ReflectionClass;
use Illuminate\Database\Eloquent\Casts\Attribute;

interface IStateModel
{
    public function getAlias(): string;

    public function uid(): Attribute;

    public static function states(): array;

    public function initialState(): ReflectionClass;

    public function currentState(): ReflectionClass;

    public function updateState(ReflectionClass|null $state): void;

}
