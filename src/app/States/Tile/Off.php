<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class Off extends StateAbstractImpl
{
    public function onTileOffClickEvent()
    {
        return On::StateClass();
    }
}
