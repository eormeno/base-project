<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;

    public function onEnter(): void
    {
        $map = $this->context->gameRepository->getMap();
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();

        $map->getTiles();
    }

    public function onTileClickEvent(int $x, int $y): void
    {
        $this->warningToast('You clicked on tile (' . $x . ', ' . $y . ')');
    }
}
