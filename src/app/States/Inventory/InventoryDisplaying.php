<?php

namespace App\States\Inventory;

use App\Models\MtqInventory;
use App\FSM\StateAbstractImpl;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];
    public ?MtqInventory $model = null;

    public function onEnter(): void
    {
        $items = $this->context->inventoryService->availableItems();
        $this->items = $this->addChilren($items, 'items');
    }
}
