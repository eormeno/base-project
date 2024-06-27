<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;

class Hidden extends StateAbstractImpl
{
    public bool $hasClue = false;
    public bool $hasFlag = false;

    protected function cast(): Tile
    {
        return $this->model;
    }

    public function onRefresh(): void {
        $this->hasClue = $this->cast()->isMarkedAsClue();
        $this->hasFlag = $this->cast()->getHasFlag();
    }

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onRefreshEvent()
    {
        $this->onRefresh();
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
