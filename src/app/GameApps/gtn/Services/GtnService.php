<?php

namespace App\GameApps\gtn\Services;

use App\Models\GameService;

class GtnService extends GameService
{
    protected $table = null;

    public function startGame()
    {
        $game = $this->getGame();
        $random_number = $this->calculateRandomNumber();
        $game->times_played++;
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->random_number = $random_number;
        $game->save();
    }

}
