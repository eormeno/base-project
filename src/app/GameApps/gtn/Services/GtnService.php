<?php

namespace App\GameApps\gtn\Services;

use App\Contracts\IPersistent;
use App\Models\GameService;

class GtnService extends GameService implements IPersistent
{
    public const TABLE = 'gtn_services';

    public static function config(): array
    {
        return [
            'min_number' =>         ['integer', 1   ],
            'max_number' =>         ['integer', 1024],
            'max_attempts' =>       ['integer', 10  ],
            'half_attempts' =>      ['integer', 5   ],
            'remaining_attempts' => ['integer', 10  ],
            'random_number' =>      ['integer', null],
            'score' =>              ['integer', 0   ],
            'times_played' =>       ['integer', 0   ],
        ];
    }

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
