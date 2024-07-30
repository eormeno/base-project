<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\StateAbstractImpl;

class GameOverTile extends StateAbstractImpl
{
    public ?MtqTile $model = null;

    public function onRestartEvent()
    {
        return Hidden::StateClass();
    }

}
