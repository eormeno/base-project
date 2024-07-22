<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MtqTile;

class Hidden extends StateAbstractImpl
{
    public bool $hasClue = false;
    public bool $hasFlag = false;

    protected function cast(): MtqTile
    {
        //return $this->context->tileRepository->getTileById($this->model->getId());
        return $this->model;
    }

    public function onEnter(): void
    {
        $this->hasClue = $this->cast()->marked_as_clue;
        $this->hasFlag = $this->cast()->has_flag;
    }

    public function onClueMarkedEvent()
    {
        $this->hasClue = $this->cast()->isMarkedAsClue();
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
        if ($this->cast()->getHasTrap()) {
            $this->sendSignal('game_over');
            return;
        }
        $this->context->gameService->revealTile($this->model);
    }
}
