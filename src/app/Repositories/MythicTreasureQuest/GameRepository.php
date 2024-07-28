<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MtqGame;
use App\Helpers\StatesLocalCache;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent
{
    public function createEmptyNewGame(): void
    {
        MtqGame::factory()->for(auth()->user())->create();
    }

    private function hasUserAGame(): bool
    {
        return auth()->user()->mtqGames()->exists();
    }

    public function getGame(): MtqGame
    {
        if (!$this->hasUserAGame()) {
            $this->createEmptyNewGame();
        }
        return auth()->user()->mtqGames;
    }

    public function restartGame(): void
    {
        $this->mapService->restartTiles();
    }

    public function reset(): void
    {
        $this->getGame()->delete();
        StatesLocalCache::reset();
    }
}
