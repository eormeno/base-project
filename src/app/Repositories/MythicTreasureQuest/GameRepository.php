<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Helpers\MapHelper;
use App\FSM\IEventListener;
use App\Traits\DebugHelper;
use App\FSM\StateChangedEvent;
use App\Services\EventManager;
use App\Helpers\InventoryHelper;
use App\Helpers\StatesLocalCache;
use App\Models\MythicTreasureQuest\Map;
use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent implements IEventListener
{
    use DebugHelper;
    private ?Map $localInMemoryMap;
    private EventManager $eventManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->localInMemoryMap = null;
        $this->eventManager = $serviceManager->eventManager;
    }

    public function createEmptyNewGame(): void
    {
        MythicTreasureQuestGame::factory()->for(auth()->user())->create();
    }

    public function getGame(): MythicTreasureQuestGame
    {
        if (!auth()->user()->mythicTreasureQuestGames()->exists()) {
            $this->createEmptyNewGame();
        }
        return auth()->user()->mythicTreasureQuestGames;
    }

    public function restartGame(): void
    {
        $this->eventManager->remove($this);
        $this->localInMemoryMap = null;
        $this->getGame()->map = MapHelper::generateMap(8, 8)->jsonSerialize();
        $this->getGame()->inventory = InventoryHelper::generateInventory()->jsonSerialize();
        $this->getGame()->save();
    }

    public function reset(): void
    {
        $this->getGame()->delete();
        StatesLocalCache::reset();
    }

    public function getMap(): Map
    {
        if ($this->localInMemoryMap) {
            return $this->localInMemoryMap;
        }
        $game = $this->getGame();
        $this->localInMemoryMap = Map::fromJson($game->map);
        $this->eventManager->add($this);
        return $this->localInMemoryMap;
    }

    public function onEvent(StateChangedEvent $event): void
    {
        $tile = $event->getModel();
        $className = class_basename($tile);
        if ($className === 'Tile') {
            $this->localInMemoryMap->replaceTile($tile);
            $this->saveMap();
        }
    }

    public function saveMap(): void
    {
        $game = $this->getGame();
        $game->map = $this->localInMemoryMap->jsonSerialize();
        $game->save();
    }

}
