<?php

namespace App\Actions\MythicTreasureQuest;

use App\Traits\ToastTrigger;
use App\Services\AbstractServiceManager;
use App\Services\MythicTreasureQuest\GameService;
use App\Services\MythicTreasureQuest\InventoryService;

class FlagAction
{
    use ToastTrigger;

    private InventoryService $inventoryService;
    private GameService $gameService;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->inventoryService = $serviceManager->get('inventoryService');
        $this->gameService = $serviceManager->get('gameService');
    }

    public function use(): void
    {
        $this->infoToast('Flag used!');
    }
}
