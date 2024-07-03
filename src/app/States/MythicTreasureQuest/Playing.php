<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuestGame;
use App\States\MythicTreasureQuest\Initial;
use App\States\MythicTreasureQuest\Flagging;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public string $strInventoryVID = '';
    public array $strArrTilesVID = [];

    private function cast(): MythicTreasureQuestGame
    {
        return $this->model;
    }

    public function onRefresh(): void
    {
        $map = $this->context->gameRepository->getMap();
        $inventory = $this->context->inventoryRepository->getInventory();
        $this->strInventoryVID = $this->context->stateManager->enqueueForRendering($inventory, $this->cast());
        $this->strArrTilesVID = $this->context->stateManager->enqueueAllForRendering($map->getTiles(), $this->cast());
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
        //$this->requireRefresh();    // TODO: Implement this in callback
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
