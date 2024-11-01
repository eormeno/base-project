<?php

namespace App\Goblets\GTN\Components;

use App\Goblets\Base\Component;

class GameConfig extends Component
{
    public int $times_played = 0;
    public int $max_attempts;
    public int $half_attempts;
    public int $min_number;
    public int $max_number;
    public int $remaining_attempts;
    public int $random_number;
    public int $score = 0;

    public function __construct(int $max_attempts, int $min_number, int $max_number)
    {
        $this->max_attempts = $max_attempts;
        $this->half_attempts = $max_attempts / 2;
        $this->min_number = $min_number;
        $this->max_number = $max_number;
    }
}
