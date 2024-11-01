<?php

namespace App\Goblets\MTQ\Components;

use App\Goblets\Base\Component;

class TileConfig extends Component
{
    public int $x = 0;
    public int $y = 0;
    public bool $has_trap = false;
    public int $traps_around = 0;
    public bool $marked_as_clue = false;
    public bool $marked_as_flag = false;

    public function __construct(
        int $x = 0,
        int $y = 0,
        bool $has_trap = false,
        int $traps_around = false,
        bool $marked_as_clue = false,
        bool $marked_as_flag = false
    ) {
        $this->x = $x;
        $this->y = $y;
        $this->has_trap = $has_trap;
        $this->traps_around = $traps_around;
        $this->marked_as_clue = $marked_as_clue;
        $this->marked_as_flag = $marked_as_flag;
    }
}
