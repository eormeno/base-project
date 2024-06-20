<?php

namespace App\Repositories\MythicTreasureQuest;

use App\FSM\IEventListener;
use App\FSM\StateChangedEvent;
use App\FSM\StatesChangeEventListeners;
use App\Models\MythicTreasureQuest\Map;
use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent implements IEventListener
{
    private ?Map $map;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->map = null;
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

    public function getMap(): Map
    {
        if ($this->map) {
            return $this->map;
        }
        $game = $this->getGame();
        $json = $game->map;
        $this->map = Map::fromJson($json, 8, 8);
        StatesChangeEventListeners::add($this);
        return $this->map;
    }

    public function onEvent(StateChangedEvent $event): void
    {
        $tile = $event->getModel();
        $id = $tile->getId();
        $className = class_basename($tile);
        if ($className === 'Tile') {
            $this->saveMap();
        }
    }

    public function saveMap(): void
    {
        $game = $this->getGame();
        $game->map = $this->map->jsonSerialize();
        $game->save();
    }

}
