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
        $items = $this->cast()->getItems();
        // remove items with quantity 0
        $items = array_filter($items, function ($item) {
            $qty = $item->getQuantity();
            return $qty > 0 || $qty == -1 ;
        });
        $this->items = $this->context->stateManager->enqueueAllForRendering($items);
    }
}
