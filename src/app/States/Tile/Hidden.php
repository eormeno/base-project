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
        if ($this->cast()->getHasTrap()) {
            $this->context->gameService->revealAll();
            $this->sendSignal('game_over');
            return;
        }
        $this->context->gameService->revealTile($this->model);
    }
}
