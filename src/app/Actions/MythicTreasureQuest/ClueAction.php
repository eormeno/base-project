<?php

namespace App\Actions\MythicTreasureQuest;

use App\Traits\ToastTrigger;
use App\Services\AbstractServiceManager;
use App\Services\MythicTreasureQuest\GameService;
use App\Services\MythicTreasureQuest\InventoryService;

class ClueAction
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
        if ($this->gameService->showClue() === false) {
            $this->errorToast('No available tiles to show clue!');
            return;
        }

        //$this->infoToast('Clue shown!');
        $this->inventoryService->decrementItemBySlug('clue');
    }
}
