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
            'cheat_number' =>       ['integer', 55555   ],
            'min_number' =>         ['integer', 1       ],
            'max_number' =>         ['integer', 1024    ],
            'max_attempts' =>       ['integer', 10      ],
            'half_attempts' =>      ['integer', 5       ],
            'remaining_attempts' => ['integer', 10      ],
            'random_number' =>      ['integer', null    ],
            'score' =>              ['integer', 0       ],
            'times_played' =>       ['integer', 0       ],
            'finished' =>           ['boolean', false   ],
        ];
    }

    public function getTable(): string
    {
        return self::TABLE;
    }

    public function getFillable(): array
    {
        return array_merge(array_keys(self::config()), ['id']);
    }

    public function startGame()
    {
        //$random_number = $this->calculateRandomNumber();
        $random_number = 512;
        $this->times_played++;
        $this->remaining_attempts = $this->max_attempts;
        $this->random_number = $random_number;
        $this->save();
    }

    public function calculateScore(): int
    {
        return $this->remaining_attempts * 100;
    }

    public function totalScore(): int
    {
        return $this->score;
    }

    public function endGame()
    {
        $this->finished = true;
        $this->score = $this->calculateScore() + $this->score;
        $this->save();
    }

    public function getRandomNumber(): int
    {
        return $this->random_number;
    }

    private function calculateRandomNumber(): int
    {
        return $this->clueService->findRandomNumber();
    }
}
