<?php

namespace App\States\Inventory;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];

    protected function cast(): Inventory
    {
        return $this->model;
    }

    public function onRefresh(): void
    {
        $this->items = $this->context->stateManager->enqueueAllForRendering($this->cast()->getItems());
    }
}
