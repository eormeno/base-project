<?php

namespace App\States\MythicTreasureQuest;

use App\States\MythicTreasureQuest\Flagging;
use App\FSM\AState;

class Playing extends AState
{
    public int $width = 8;
    public int $height = 8;
    public string $strMapVID = '';
    public string $strInventoryVID = '';
    protected string $mtqMaps;
    protected string $mtqInventories;

    public function onEnter(): void
    {
        $map = $this->context->mapService->getMap();
        $this->width = $map->width;
        $this->height = $map->height;
        $this->strMapVID = $this->addChild($map, 'strMapVID');
        $inventory = $this->context->inventoryService->getInventory();
        $this->strInventoryVID = $this->addChild($inventory, 'strInventoryVID');
    }

    public function onFlagEvent()
    {
        return Flagging::StateClass();
    }

    public function onGameOverEvent()
    {
        return GameOver::StateClass();
    }

}
