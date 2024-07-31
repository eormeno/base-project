<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\StateAbstractImpl;

class Revealed extends StateAbstractImpl
{
    public ?MtqTile $model = null;

    public function onGameOverEvent()
    {
        return GameOverTile::StateClass();
    }
}
