<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryRepository extends AbstractServiceComponent
{
    private ?Inventory $localInMemoryInventory;
    private GameRepository $gameRepository;
    private MythicTreasureQuestItemRepository $mythicTreasureQuestItemRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->localInMemoryInventory = null;
        $this->gameRepository = $serviceManager->get('gameRepository');
        $this->mythicTreasureQuestItemRepository = $serviceManager->get('mythicTreasureQuestItemRepository');
    }

    public function getInventory(): Inventory
    {
        if ($this->localInMemoryInventory) {
            return $this->localInMemoryInventory;
        }
        $game = $this->gameRepository->getGame();
        $this->localInMemoryInventory = Inventory::fromJson($game->inventory);
        return $this->localInMemoryInventory;
    }

    public function decrementItemBySlug(string $slug): void
    {
        $itemTypeInfo = $this->mythicTreasureQuestItemRepository->getItemInfoBySlug($slug);
        if (!$itemTypeInfo) {
            return;
        }
        $inventory = $this->getInventory();
        $item = $inventory->getItemByTypeId($itemTypeInfo['id']);
        if ($item) {
            $item->decrementQuantity();
            $this->saveInventory($inventory);
        }
    }

    public function saveInventory(Inventory $inventory): void
    {
        $game = $this->gameRepository->getGame();
        $game->inventory = $inventory->jsonSerialize();
        $game->save();
        $this->localInMemoryInventory = $inventory;
    }
}
