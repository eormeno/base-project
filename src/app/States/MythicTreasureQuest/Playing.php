<?php

namespace App\States\MythicTreasureQuest;

use App\States\MythicTreasureQuest\Flagging;

class Playing extends APlayingStates
{
    public string $strInventoryVID = '';

    public function onEnter(): void
    {
        parent::onEnter();
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
