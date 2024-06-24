<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;

class Revealed extends StateAbstractImpl
{
    private Tile $tile;
    public bool $hasTrap = false;
    public int $trapsAround = 0;

    public function onRefresh(): void
    {
        $this->tile = $this->model;
        $this->hasTrap = $this->tile->getHasTrap();
        $this->trapsAround = $this->tile->getTrapsAround();
    }
}
