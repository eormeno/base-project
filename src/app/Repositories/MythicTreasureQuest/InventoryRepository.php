<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MtqGameItem;
use App\Models\MtqInventory;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Traits\DebugHelper;

class InventoryRepository extends AbstractServiceComponent
{
    use DebugHelper;
    private GameRepository $gameRepository;
    private MythicTreasureQuestItemRepository $mythicTreasureQuestItemRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->gameRepository = $serviceManager->get('gameRepository');
        $this->mythicTreasureQuestItemRepository = $serviceManager->get('mythicTreasureQuestItemRepository');
    }

    public function getInventory2(): MtqInventory
    {
        return $this->gameRepository->getGame2()->mtqInventories()->first();
    }

    public function decrementItemBySlug(string $slug): MtqGameItem | null
    {
        $itemTypeInfo = $this->mythicTreasureQuestItemRepository->getItemInfoBySlug($slug);
        if (!$itemTypeInfo) {
            return null;
        }
        $inventory = $this->getInventory2();
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
