<?php

namespace App\States\MythicTreasureQuest;

use App\States\MythicTreasureQuest\Flagging;

class Playing extends APlayingStates
{
    public string $strInventoryVID = '';

    public function onRefresh(): void
    {
        parent::onRefresh();
        $inventory = $this->context->inventoryRepository->getInventory2();
        $this->strInventoryVID = $this->addChild($inventory);
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
