<?php

namespace App\States\Inventory;

use App\Models\MtqInventory;
use App\FSM\StateAbstractImpl;

class InventoryDisplaying extends StateAbstractImpl
{
    public array $items = [];
    public int $itemsCount = 3;
    public ?MtqInventory $model = null;

    public function onEnter(): void
    {
        $items = $this->filterAvailableItems();
        $this->itemsCount = count($items);
        $this->items = $this->addChilren($items, 'items');
    }

    private function filterAvailableItems(): array
    {
        return $this->model->mtqGameItems()->get()->filter(function ($item) {
            return $item->quantity > 0;
        })->all();
    }
}
