<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;

class Hidden extends StateAbstractImpl
{
    protected function cast(): Tile
    {
        return $this->model;
    }

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onTileOffClickEvent()
    {
        $this->context->gameService->revealTile($this->cast());
    }
}
