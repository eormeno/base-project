<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MtqTile;

class Hidden extends StateAbstractImpl
{
    protected function cast(): MtqTile
    {
        return $this->model;
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
