<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;
use App\States\MythicTreasureQuest\Flagging;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public string $strInventoryVID = '';
    public array $strArrTilesVID = [];

    public function onEnter(): void
    {
        $map = $this->context->gameRepository->getMap();
        $inventory = $this->context->inventoryRepository->getInventory();

        $this->width = $map->getWidth();
        $this->height = $map->getHeight();

        $this->strInventoryVID = $this->addChild($inventory);
        $this->strArrTilesVID  = $this->addChilren($map->getTiles());
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
