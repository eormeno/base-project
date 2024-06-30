<?php

namespace App\Services\MythicTreasureQuest;

use App\Services\AbstractServiceManager;
use App\Repositories\Globals\UserRepository;
use App\Repositories\MythicTreasureQuest\GameRepository;
use App\Repositories\MythicTreasureQuest\TileRepository;
use App\Repositories\MythicTreasureQuest\InventoryRepository;
use App\Repositories\MythicTreasureQuest\MythicTreasureQuestItemRepository;

class MythicTreasureQuestServiceManager extends AbstractServiceManager
{
    public function __construct()
    {
        parent::__construct();
        $this->addService('userRepository', new UserRepository($this));
        $this->addService('gameRepository', new GameRepository($this));
        $this->addService('tileRepository', new TileRepository($this));
        $this->addService('mythicTreasureQuestItemRepository', new MythicTreasureQuestItemRepository($this));
        $this->addService('inventoryRepository', new InventoryRepository($this));
        $this->addService('gameService', new GameService($this));
    }
}
