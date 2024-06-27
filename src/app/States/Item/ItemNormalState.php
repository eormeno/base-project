<?php

namespace App\States\Item;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Item;

class ItemNormalState extends StateAbstractImpl
{
    public int $id;
    public string $icon;
    public string $name;

    public int $quantity;

    protected function cast(): Item
    {
        return $this->model;
    }

    public function onSelectEvent(int $item)
    {
        $this->infoToast("You selected item with id: $item");
    }

    public function onRefresh(): void
    {
        $this->id = $this->cast()->getId();
        $itemId = $this->cast()->getItemId();
        $itemInfo = $this->context->inventoryRepository->getItemInfo($itemId);
        $this->icon = $itemInfo['icon'];
        $this->name = $itemInfo['name'];
        $this->quantity = $this->cast()->getQuantity();
    }
}
