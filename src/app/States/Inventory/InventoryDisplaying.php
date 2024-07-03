<?php

namespace App\States\Inventory;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Inventory;
use App\Traits\DebugHelper;

class InventoryDisplaying extends StateAbstractImpl
{
    use DebugHelper;
    public array $items = [];
    public int $itemsCount = 0;

    protected function cast(): Inventory
    {
        return $this->model;
    }

    public function onEnter(): void
    {
        $items = $this->filterAvailableItems();
        $this->itemsCount = count($items);
        $this->items = $this->addChilren($items);
    }

    private function filterAvailableItems(): array
    {
        $items = $this->cast()->getItems();
        return array_filter($items, function ($item) {
            return $item->getQuantity() > 0;
        });
    }
}
