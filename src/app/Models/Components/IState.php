<?php

namespace App\Models\Components;

interface IState
{
    public static function state(): string | null;
    public function handleStateEvent(array $event): string | null;
    public function onEnter(): void;
    public function onExit(): void;
    public function passTo(): string | null;
}
