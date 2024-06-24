<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;
use App\States\MythicTreasureQuest\Initial;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public array $list = [];

    public bool $playAgain = false;

    public function onEnter(): void{
        $this->playAgain = false;
    }

    public function onRefresh(): void
    {
        $map = $this->context->gameRepository->getMap();
        $this->list = $this->context->stateManager->enqueueAllForRendering($map->getTiles());
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
    }

    public function onPlayAgainEvent()
    {
        return Initial::StateClass();
    }

    public function onGameOverEvent(): void
    {
        $this->errorToast('Game Over');
        $this->playAgain = true;
    }

}
