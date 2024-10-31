<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\PlayingStateRenderer;

class Game
{
    public string $sate = 'initial';
    private array $components = [];

    public function __construct()
    {
        $this->components[] = new StateRenderer('initial');
        $this->components[] = new StateRenderer('game_over');
        $this->components[] = new PlayingStateRenderer('playing');
    }
}
