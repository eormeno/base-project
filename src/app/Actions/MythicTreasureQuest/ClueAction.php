<?php

namespace App\Actions\MythicTreasureQuest;

use App\Traits\ToastTrigger;
use App\Services\AbstractServiceManager;
use App\Services\MythicTreasureQuest\GameService;
use App\Repositories\MythicTreasureQuest\InventoryRepository;

class ClueAction
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
        if ($this->gameService->showClue() === false) {
            $this->errorToast('No available tiles to show clue!');
            return;
        }

        $this->infoToast('Clue shown!');
        $this->inventoryRepository->decrementItemBySlug('clue');
    }
}
