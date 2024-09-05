<?php

namespace App\States\Inventory;

use App\FSM\AState;

class InventoryDisplaying extends AState
{
    public array $items = [];

    public function onEnter(): void
    {
        $items = $this->context->inventoryService->availableItems();
        $this->items = $this->addChilren($items, 'items');
    }

    public function getSubStates(): array
    {
        return [
            'items' => $this->items,
        ];
    }

    public function onModificadoEvent()
    {
        // $this->reset();
        // $items = $this->context->inventoryService->availableItems();
        // $this->items = $this->addChilren($items, 'items');
        // refresh children
        $this->requireRefresh();
    }
}
