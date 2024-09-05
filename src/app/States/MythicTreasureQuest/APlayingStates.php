<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\AState;

abstract class APlayingStates extends AState
{
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
}
