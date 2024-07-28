<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\StateAbstractImpl;

class Hidden extends StateAbstractImpl
{
    protected function cast(): MtqTile
    {
        return $this->model;
    }

    public function onRefresh(): void
    {
        $this->cast()->refresh();
    }

    public function onClueMarkedEvent()
    {
        $this->cast()->refresh();
    }

    public function onFlagEvent()
    {
        return FlaggingTile::StateClass();
    }

    public function onGameOverEvent()
    {
        return GameOverTile::StateClass();
    }

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onTileClickedEvent()
    {
        if ($this->cast()->has_trap) {
            $this->sendSignal('game_over');
            return;
        }
        $this->context->gameService->revealTile($this->model);
    }
}
