<?php

namespace App\Services\MythicTreasureQuest;

use App\Services\AbstractServiceManager;
use App\Repositories\Globals\UserRepository;
use App\Repositories\MythicTreasureQuest\GameRepository;
use App\Repositories\MythicTreasureQuest\InventoryRepository;

class MythicTreasureQuestServiceManager extends AbstractServiceManager
{
    public function __construct()
    {
        $this->addService('gameService', new GameService($this));
        $this->addService('gameRepository', new GameRepository($this));
        $this->addService('inventoryRepository', new InventoryRepository($this));
        $this->addService('userRepository', new UserRepository($this));
    }
}
