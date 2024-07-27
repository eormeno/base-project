<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MtqGame;
use App\Helpers\MapHelper;
use App\FSM\IEventListener;
use App\FSM\StateChangedEvent;
use App\Services\EventManager;
use App\Helpers\InventoryHelper;
use App\Helpers\StatesLocalCache;
use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent implements IEventListener
{
    private EventManager $eventManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->eventManager = $serviceManager->eventManager;
    }

    public function createEmptyNewGame(): void
    {
        MythicTreasureQuestGame::factory()->for(auth()->user())->create();
    }

    public function createEmptyNewGame2(): void
    {
        MtqGame::factory()->for(auth()->user())->create();
    }

    private function hasUserAGame(): bool
    {
        return auth()->user()->mythicTreasureQuestGames()->exists();
    }

    private function hasUserAGame2(): bool
    {
        return auth()->user()->mtqGames()->exists();
    }

    private function getCurrentUserGame(): MythicTreasureQuestGame
    {
        return auth()->user()->mythicTreasureQuestGames;
    }

    public function getGame(): MythicTreasureQuestGame
    {
        if (!$this->hasUserAGame()) {
            $this->createEmptyNewGame();
        }
        return $this->getCurrentUserGame();
    }

    public function getGame2(): MtqGame
    {
        if (!$this->hasUserAGame2()) {
            $this->createEmptyNewGame2();
        }
        return auth()->user()->mtqGames;
    }

    public function restartGame(): void
    {
        $this->getGame2()->delete();
        $this->createEmptyNewGame2();
        // $this->eventManager->remove($this);
        // $this->getGame()->map = MapHelper::generateMap(8, 8)->jsonSerialize();
        // $this->getGame()->inventory = InventoryHelper::generateInventory()->jsonSerialize();
        // $this->getGame()->save();
    }

    public function reset(): void
    {
        $this->getGame()->delete();
        $this->getGame2()->delete();
        StatesLocalCache::reset();
    }

    public function onEvent(StateChangedEvent $event): void
    {
    }
}
