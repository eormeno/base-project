<?php

namespace App\States\Map;

use App\FSM\StateAbstractImpl;

class MapDisplaying extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public array $strArrTilesVID = [];

    public function onEnter(): void
    {
        $map = $this->context->mapService->getMap2();
        $tiles = $this->context->mapService->getMap2Tiles();
        $this->width = $map->width;
        $this->height = $map->height;
        $this->strArrTilesVID = $this->addChilren($tiles);
    }

    public function onGameOverEvent()
    {
        $this->requireRefresh();
    }
}
