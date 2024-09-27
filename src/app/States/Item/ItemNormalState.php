<?php

namespace App\States\Item;

use App\Models\MtqGameItem;
use App\FSM\AState;

class ItemNormalState extends AState
{
    public ?MtqGameItem $parentModel = null;
    public int $id;
    public string $slug;
    public string $icon;
    public string $name;
    public int $quantity;
    private array $itemInfo = [];

    public function onSelectEvent(string $slug)
    {
        if ($slug === 'flag') {
            $this->sendSignal('flag');
            return;
        }
        if ($slug === 'clue') {
            $this->doClue();
            return;
        }
    }

    private function doClue(): void
    {
        $item = $this->context->inventoryService->decrementItemBySlug('clue');
        if (!$item) {
            $this->errorToast('No available clues!');
            return;
        }
        if ($this->context->gameService->showClue() === false) {
            $this->warningToast('No available tiles to show clue!');
            return;
        }
        if ($item->quantity === 0) {
            $this->warningToast('No more clues!');
            $inventory = $this->context->inventoryService->getInventory();
            $this->sendEventTo('modificado', $inventory);
        }
        $this->quantity = $item->quantity;
        $this->requireRefresh();
    }

    public function onRefresh(): void
    {
        $this->parentModel->refresh();
        $this->id = $this->parentModel->id;
        $itemId = $this->parentModel->mtqItemClass()->first()->id;
        $this->itemInfo = $this->context->mtqItemClassRepository->getItemInfo($itemId);
        $this->slug = $this->itemInfo['slug'];
        $this->icon = $this->itemInfo['icon'];
        $this->name = $this->itemInfo['name'];
        $this->quantity = $this->parentModel->quantity;
    }
}
