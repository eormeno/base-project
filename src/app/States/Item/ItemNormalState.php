<?php

namespace App\States\Item;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Item;
use App\Traits\DebugHelper;

class ItemNormalState extends StateAbstractImpl
{
    use DebugHelper;
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
        if ($slug==='clue'){
            $this->doClue();
            return;
        }
    }

    private function doClue(): void
    {
        if ($this->context->gameService->showClue() === false) {
            $this->errorToast('No available tiles to show clue!');
            return;
        }
        $item = $this->context->inventoryRepository->decrementItemBySlug('clue');
        if (!$item) {
            $this->errorToast('No available clues!');
            return;
        }
        $this->quantity = $item->getQuantity();
    }


    public function onEnter(): void
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
