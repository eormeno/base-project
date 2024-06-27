<?php

namespace App\Actions\MythicTreasureQuest;

use App\Traits\ToastTrigger;
use App\Services\AbstractServiceManager;
use App\Services\MythicTreasureQuest\GameService;
use App\Repositories\MythicTreasureQuest\InventoryRepository;

class FlagAction
{
    use ToastTrigger;

    private InventoryRepository $inventoryRepository;
    private GameService $gameService;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->inventoryRepository = $serviceManager->get('inventoryRepository');
        $this->gameService = $serviceManager->get('gameService');
    }

    public function use(): void
    {
        $this->infoToast('Flag used!');
    }
}
