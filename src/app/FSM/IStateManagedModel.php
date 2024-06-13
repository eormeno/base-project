<?php

namespace App\FSM;

interface IStateManagedModel
{
    public function getId(): int;

    public static function getInitialStateClass(): string;

    public function getState(): string;

    public function setState(string $state): void;

}
