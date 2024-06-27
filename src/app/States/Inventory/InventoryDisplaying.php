<?php

namespace App\States\Inventory;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];
    public int $itemsCount = 0;

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
            return $qty > 0;
        });
        $this->itemsCount = count($items);
        $this->items = $this->context->stateManager->enqueueAllForRendering($items);
    }
}
