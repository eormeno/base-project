<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;

class Hidden extends StateAbstractImpl
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

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onTileOffClickEvent()
    {
        $this->context->gameService->revealTile2($this->model);
        //$this->sendEvent('reveal');
    }
}
