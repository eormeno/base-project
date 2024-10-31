<?php

namespace App\Goblets\MTQ;

use App\Goblets\MTQ\Prefabs\Game;

class Main {
    public string $name = 'Mythic Treasure Quest';
    public Game $game;

    public function __construct()
    {
        $this->game = new Game();
    }
}
