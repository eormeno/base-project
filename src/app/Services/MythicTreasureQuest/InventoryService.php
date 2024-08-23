<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqGameItem;
use App\Models\MtqInventory;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Repositories\MythicTreasureQuest\GameRepository;
use App\Repositories\MythicTreasureQuest\MtqItemClassRepository;

class InventoryService extends AbstractServiceComponent
{
    private GameRepository $gameRepository;
    private MtqItemClassRepository $mtqItemClassRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->gameRepository = $serviceManager->get('gameRepository');
        $this->mtqItemClassRepository = $serviceManager->get('mtqItemClassRepository');
    }

    public function getInventory(): MtqInventory
    {
        return $this->gameRepository->getGame()->mtqInventories()->first();
    }

    public function availableItems(): array
    {
        return $this->getInventory()->mtqGameItems()->get()->filter(function ($item) {
            return $item->quantity > 0;
        })->all();
    }

    public function decrementItemBySlug(string $slug): MtqGameItem | null
    {
        $itemTypeInfo = $this->mtqItemClassRepository->getItemInfoBySlug($slug);
        if (!$itemTypeInfo) {
            return null;
        }
        $inventory = $this->getInventory();
        $items = $inventory->mtqGameItems()->get();
        $item = $items->where('mtq_item_class_id', $itemTypeInfo['id'])->first();
        if ($item && $item->quantity > 0) {
            $item->quantity--;
            $item->save();
            $this->requireRefresh($inventory);
            return $item;
        }
        return null;
    }

}
