<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqGame;
use App\Models\MtqMap;
use App\Models\MythicTreasureQuest\Map;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Traits\DebugHelper;

class MapService extends AbstractServiceComponent
{
    use DebugHelper;
    private ?Map $localInMemoryMap;
    private MtqGame $castedGame;

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

    public function getMap2(): MtqMap
    {
        $this->castedGame = $this->gameRepository->getGame2();
        return $this->castedGame->mtqMaps()->first();
    }

    public function getMap2Tiles() : array
    {
        $map = $this->getMap2();
        return $map->tiles()->get()->all();
    }

}
