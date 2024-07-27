<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

abstract class APlayingStates extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public string $strMapVID = '';

    public function onRefresh(): void
    {
        $map = $this->context->mapService->getMap();
        $this->width = $map->width;
        $this->height = $map->height;
        $this->strMapVID = $this->addChild($map);
    }
}
