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
        $map = $this->context->mapService->getMap();
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
        $this->strArrTilesVID = $this->addChilren($map->getTiles());
    }
}
