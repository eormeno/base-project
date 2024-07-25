<?php

namespace App\FSM;

use ReflectionClass;
use Illuminate\Support\Carbon;

interface IStateModel
{
    public function getId(): int;

    public function getAlias(): string;

    public static function getInitialStateClass(): ReflectionClass;

    public function getState(): string|null;

    public function updateState(string|null $state): void;

    public function getStartedAt(): Carbon|null;

    public function setStartedAt(Carbon|null $startedAt): void;

}
