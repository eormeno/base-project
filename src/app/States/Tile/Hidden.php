<?php

namespace App\States\Tile;

use App\Models\MtqTile;
use App\FSM\StateAbstractImpl;

class Hidden extends StateAbstractImpl
{
    public ?MtqTile $model = null;

    public function onRefresh(): void
    {
        $this->model->refresh();
    }

    public function onClueMarkedEvent()
    {
        $this->model->refresh();
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
        if ($this->model->has_trap) {
            $this->sendSignal('game_over');
            return;
        }
        $this->context->gameService->revealTile($this->model);
    }
}
