<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Tile;
use App\Traits\DebugHelper;

class Hidden extends StateAbstractImpl
{
    use DebugHelper;
    public bool $hasClue = false;
    public bool $hasFlag = false;

    protected function cast(): Tile
    {
        return $this->context->tileRepository->getTileById($this->model->getId());
    }

    public function onEnter(): void
    {
        $this->hasClue = $this->cast()->isMarkedAsClue();
        $this->hasFlag = $this->cast()->getHasFlag();
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
