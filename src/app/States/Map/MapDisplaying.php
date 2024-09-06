<?php

namespace App\States\Map;

use App\FSM\AState;

class MapDisplaying extends AState
{
    public int $width;
    public int $height;
    public array $strArrTilesVID = [];
    public array $tiles = [];

    public function onEnter(): void
    {
        // $map = $this->context->mapService->getMap();
        $tiles = $this->context->mapService->getMap2Tiles();
        // $this->width = $map->width;
        // $this->height = $map->height;
        $this->strArrTilesVID = $this->addChilren($tiles, 'strArrTilesVID');
    }

    public function onRefresh(): void
    {
        $this->log("MapDisplaying::onRefresh");
        $this->log("width: $this->width, height: $this->height");
        $this->log(json_encode($this->tiles));
    }
}
