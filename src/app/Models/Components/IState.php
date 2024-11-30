<?php

namespace App\Models\Components;

interface IState
{
    public static function state(): string | null;
    public function handle(array $event): string;
    public function onEnter(): void;
    public function onExit(): void;
}
