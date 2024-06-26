<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestItem;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryRepository extends AbstractServiceComponent
{
    private ?Inventory $localInMemoryInventory;
    private GameRepository $gameRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->localInMemoryInventory = null;
        $this->gameRepository = $serviceManager->get('gameRepository');
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

    public function getItemInfo(int $itemId): array
    {
        $item = MythicTreasureQuestItem::findOrFail($itemId);
        $icon = $item->icon;
        $name = $item->name;
        $description = $item->description;
        return compact('icon', 'name', 'description');
    }

}
