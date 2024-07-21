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
        $map = $this->context->mapService->getMap2();
        $this->width = $map->width;
        $this->height = $map->height;
        //$this->strArrTilesVID  = $this->addChilren($map->getTiles());
        $this->strMapVID = $this->addChild($map);
    }

}
