<?php

namespace App\Goblets\GTN\Components;

use App\Goblets\Base\Component;

class GameConfig extends Component
{
    public function __construct(int $max_attempts, int $min_number, int $max_number)
    {
        $this->addAttribute('times_played', 0, Component::INTEGER);
        $this->addAttribute('max_attempts', $max_attempts, Component::INTEGER);
        $this->addAttribute('half_attempts', $max_attempts / 2, Component::INTEGER);
        $this->addAttribute('min_number', $min_number, Component::INTEGER);
        $this->addAttribute('max_number', $max_number, Component::INTEGER);
        $this->addAttribute('score', 0, Component::INTEGER);
    }
}
