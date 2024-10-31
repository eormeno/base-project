<?php

namespace App\Goblets\GTN\Prefabs;

use App\Goblets\Components\StateRenderer;
use App\Goblets\GTN\Components\GameConfig;

class Game
{
    public string $sate = 'initial';
    private array $components = [];

    public function __construct()
    {
        $this->components[] = new GameConfig(10, 1, 1024);
        $this->components[] = new StateRenderer('initial');
        $this->components[] = new StateRenderer('asking_to_play');
        $this->components[] = new StateRenderer('game_over');
        $this->components[] = new StateRenderer('playing');
        $this->components[] = new StateRenderer('preparing');
        $this->components[] = new StateRenderer('showing_clue');
        $this->components[] = new StateRenderer('success');
    }
}
