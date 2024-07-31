<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    use \App\Traits\DebugHelper;
    public int $width = 8;
    public int $height = 8;
    public string $strMapVID = '';

    public function onEnter(): void
    {
        $map = $this->context->mapService->getMap();
        $this->width = $map->width;
        $this->height = $map->height;
        $this->strMapVID = $this->addChild($map, 'strMapVID');
    }

    public function onRestartEvent()
    {
        return Initial::StateClass();
    }

    public function onPlayAgainEvent()
    {
        $this->context->gameRepository->restartGame();
        $this->sendSignal('restart');
    }
}
