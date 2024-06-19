<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;

    public array $list = [];

    public function onRefresh(): void
    {
        $map = $this->context->gameRepository->getMap();
        $this->list = $this->context->stateManager->enqueueAllForRendering($this->context->serviceManager, $map->getTiles());
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
    }

    // public function onTileClickEvent(int $x, int $y): void
    // {
    //     $this->warningToast('You clicked on tile (' . $x . ', ' . $y . ')');
    // }
}
