<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\AState;

class GameOverTile extends AState
{
    public ?MtqTile $parentModel = null;

    public function onRestartEvent()
    {
        return Hidden::StateClass();
    }

}
