<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class On extends StateAbstractImpl
{
    public function onTileOnClickEvent()
    {
        return Off::StateClass();
    }
}
