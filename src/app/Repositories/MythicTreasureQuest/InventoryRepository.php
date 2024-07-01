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
            $this->saveInventory();

            //$this->requireRefresh($inventory);
            // lo que pasa, es que refrescar un contenedor de items, no refresca los items en si, sino que refresca el contenedor.
            // NECESITO establecer la relación padre-hijo entre el contenedor y los items, para que al refrescar el contenedor, se refresquen los items.
            $this->requireRefresh($item);
        }
    }

    public function saveInventory(): void
    {
        $inventory = $this->getInventory();
        $game = $this->gameRepository->getGame();
        $game->inventory = $inventory->jsonSerialize();
        $game->save();
    }
}
