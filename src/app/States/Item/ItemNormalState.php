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
    private array $itemInfo = [];

    protected function cast(): Item
    {
        return $this->model;
    }

    public function onSelectEvent(int $item)
    {
        $description = $this->itemInfo['description'];
        $this->infoToast($description);
    }

    public function onRefresh(): void
    {
        $this->id = $this->cast()->getId();
        $itemId = $this->cast()->getItemId();
        $this->itemInfo = $this->context->inventoryRepository->getItemInfo($itemId);
        $this->icon = $this->itemInfo['icon'];
        $this->name = $this->itemInfo['name'];
        $this->quantity = $this->cast()->getQuantity();
    }
}
