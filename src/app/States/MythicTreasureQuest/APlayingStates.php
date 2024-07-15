<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

abstract class APlayingStates extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    //public array $strArrTilesVID = [];
    public string $strMapVID = '';

    public function onEnter(): void
    {
        $map = $this->context->mapService->getMap();
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
        //$this->strArrTilesVID  = $this->addChilren($map->getTiles());
        $this->strMapVID = $this->addChild($map);
    }

}
