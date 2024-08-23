<?php

namespace App\States\Inventory;

use App\FSM\StateAbstractImpl;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];

    public function onEnter(): void
    {
        $items = $this->context->inventoryService->availableItems();
        $this->items = $this->addChilren($items, 'items');
    }
}
