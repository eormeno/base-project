<?php

namespace App\States\Tile;

use App\Models\MythicTreasureQuest\Tile;

class GameOverTile extends Revealed
{
    private Tile $tile;
    public bool $hasTrap = false;

    public function onEnter(): void
    {
        $this->tile = $this->model;
        $this->hasTrap = $this->tile->getHasTrap();
    }
}
