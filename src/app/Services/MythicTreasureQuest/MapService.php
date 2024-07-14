<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuest\Map;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class MapService extends AbstractServiceComponent
{
    private ?Map $localInMemoryMap;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->localInMemoryMap = null;
    }

    public function getMap(): Map
    {
        if ($this->localInMemoryMap) {
            return $this->localInMemoryMap;
        }
        $game = $this->gameRepository->getGame();
        $this->localInMemoryMap = Map::fromField($game, 'map');
        return $this->localInMemoryMap;
    }
}
