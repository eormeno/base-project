<?php

namespace App\States\Map;

use App\FSM\StateAbstractImpl;

class MapDisplaying extends StateAbstractImpl
{
    use \App\Traits\DebugHelper;
    public int $width = 8;
    public int $height = 8;
    public array $strArrTilesVID = [];

    public function onRefresh(): void
    {
        $this->log("MapDisplaying::onRefresh");
        $map = $this->context->mapService->getMap();
        $tiles = $this->context->mapService->getMap2Tiles();
        $this->width = $map->width;
        $this->height = $map->height;
        $this->strArrTilesVID = $this->addChilren($tiles);
        $this->requireRefresh();
    }

    public function onGameOverEvent()
    {
        $this->requireRefresh();
    }
}
