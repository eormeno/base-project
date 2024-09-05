<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\AState;

class Revealed extends AState
{
    public ?MtqTile $model = null;

    public function _onGameOverEvent()
    {
        return GameOverTile::StateClass();
    }
}
