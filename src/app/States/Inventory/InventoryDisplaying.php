<?php

namespace App\States\Inventory;

use App\Models\MtqInventory;
use App\FSM\StateAbstractImpl;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];
    public int $itemsCount = 0;

    protected function cast(): MtqInventory
    {
        return $this->model;
    }

    public function onEnter(): void
    {
        $items = $this->filterAvailableItems();
        $this->itemsCount = count($items);
        $this->items = $this->addChilren($items);
    }

    public function onRefresh(): void
    {
        $this->onEnter();
    }

    private function filterAvailableItems(): array
    {
        return $this->cast()->mtqGameItems()->get()->filter(function ($item) {
            return $item->quantity > 0;
        })->all();
    }
}
