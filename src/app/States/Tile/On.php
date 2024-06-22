<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class On extends StateAbstractImpl
{
    public string $showTrap = '';

    public function onRefresh(): void
    {
        $this->showTrap = $this->model->hasTrap ? 'x' : '';
    }

    public function onTileOnClickEvent()
    {
        return Off::StateClass();
    }
}
