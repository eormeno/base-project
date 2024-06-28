<?php

namespace App\States\Item;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Item;

class ItemNormalState extends StateAbstractImpl
{
    public int $id;

    public string $slug;
    public string $icon;
    public string $name;
    public int $quantity;
    private array $itemInfo = [];

    protected function cast(): Item
    {
        return $this->model;
    }

    public function onSelectEvent(string $slug)
    {
        if ($slug==='flag'){
            $this->sendSignal('flag');
            return;
        }
        $this->doAction($slug, 'use');
    }

    public function onRefresh(): void
    {
        $this->id = $this->cast()->getId();
        $itemId = $this->cast()->getItemId();
        $this->itemInfo = $this->context->mythicTreasureQuestItemRepository->getItemInfo($itemId);
        $this->slug = $this->itemInfo['slug'];
        $this->icon = $this->itemInfo['icon'];
        $this->name = $this->itemInfo['name'];
        $this->quantity = $this->cast()->getQuantity();
    }
}
