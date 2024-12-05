<?php

namespace App\GameApps\gtn\Services;

use App\Contracts\IPersistent;
use App\Models\GameService;

class GtnService extends GameService implements IPersistent
{
    public const TABLE = 'gtn_services';

    protected $fillable = [
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
        'random_number',
        'score',
        'times_played',
    ];

    public function getTable(): string
    {
        return self::TABLE;
    }

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
