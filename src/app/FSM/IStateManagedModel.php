<?php

namespace App\FSM;

use ReflectionClass;

interface IStateManagedModel
{
    public function getId(): int;

    public function __get($name): mixed;

    public static function getInitialStateClass(): ReflectionClass;

    public function getState(): string|null;

    public function updateState(string|null $state): void;

}
