<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\AState;

class GameOverTile extends AState
{
    public ?MtqTile $model = null;

    public function onRestartEvent()
    {
        return Hidden::StateClass();
    }

}
