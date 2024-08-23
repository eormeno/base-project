<?php

namespace App\FSM;

use ReflectionClass;
use Illuminate\Support\Carbon;

interface IStateModel
{
    public function getId(): int;

    public function getAlias(): string;

    public function parent(): IStateModel | null;

    public static function getInitialStateClass(): ReflectionClass;

    public function getState(): string|null;

    public function updateState(string|null $state): void;

    public function getEnteredAt(): string|null;

    public function setEnteredAt(Carbon|string|null $enteredAt): void;

}
