<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\StateAbstractImpl;

class Revealed extends StateAbstractImpl
{
    private MtqTile $tile;

    protected function cast(): MtqTile
    {
        return $this->model;
    }

    public function onGameOverEvent()
    {
        return GameOverTile::StateClass();
    }

    public function onRefresh(): void
    {
        $this->cast()->refresh();
    }
}
