<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\AState;

class Revealed extends AState
{
    public ?MtqTile $parentModel = null;

    public function _onGameOverEvent()
    {
        return GameOverTile::StateClass();
    }
}
