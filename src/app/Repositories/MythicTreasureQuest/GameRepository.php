<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent
{
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

}
