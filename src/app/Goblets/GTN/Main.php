<?php

namespace App\Goblets\GTN;

use App\Goblets\GTN\Prefabs\Game;

class Main
{
    public string $name = 'Guess The Number';
    public Game $game;

    public function __construct()
    {
        $this->game = new Game();
    }
}
