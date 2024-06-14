<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function passTo()
    {
        return On::StateClass();
    }
}
