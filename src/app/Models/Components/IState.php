<?php

namespace App\Models\Components;

interface IState
{
    public function onEnter(): void;
    public function onExit(): void;
}
