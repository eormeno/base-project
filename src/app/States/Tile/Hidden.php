<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;

class Hidden extends StateAbstractImpl
{
    public bool $hasTrap = false;
    public int $trapsAround = 0;

    protected function cast(): Tile
    {
        return $this->model;
    }

    public function onRefresh(): void
    {
        $this->hasTrap = $this->cast()->getHasTrap();
        $this->trapsAround = $this->cast()->getTrapsAround();
    }

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onTileOffClickEvent()
    {
        $this->context->gameService->revealTile($this->cast());
        //$this->sendEvent('reveal');
    }
}
