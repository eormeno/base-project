<?php

namespace App\Goblets\GTN\Components;

use App\Goblets\Base\Component;

class GameConfig extends Component
{
    private int $times_played = 0;
    private int $half_attempts;
    private int $score = 0;

    public function __construct(
        private int $max_attempts,
        private int $min_number,
        private int $max_number
    ) {
        $this->half_attempts = $max_attempts / 2;
        $this->min_number = $min_number;
        $this->max_number = $max_number;
    }
}
