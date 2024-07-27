<?php

namespace App\Services\MythicTreasureQuest;

use App\Services\AbstractServiceManager;
use App\Repositories\Globals\UserRepository;
use App\Services\MythicTreasureQuest\TileService;
use App\Repositories\MythicTreasureQuest\GameRepository;
use App\Services\MythicTreasureQuest\InventoryService;
use App\Repositories\MythicTreasureQuest\MtqItemClassRepository;

class MythicTreasureQuestServiceManager extends AbstractServiceManager
{
    public function __construct()
    {
        parent::__construct();
        $this->addService('userRepository', new UserRepository($this));
        $this->addService('gameRepository', new GameRepository($this));
        $this->addService('tileService', new TileService($this));
        $this->addService('mtqItemClassRepository', new MtqItemClassRepository($this));
        $this->addService('inventoryService', new InventoryService($this));
        $this->addService('gameService', new GameService($this));
        $this->addService('mapService', new MapService($this));
    }
}
