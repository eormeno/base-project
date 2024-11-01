<?php

namespace App\Goblets\GTN\Prefabs;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\GTN\Components\GameConfig;

class Game extends GameObject
{
    public string $sate = 'initial';

    public function __construct()
    {
        $this->addComponent(new GameConfig(10, 1, 1024));
        $this->addComponent(new StateRenderer('initial'));
        $this->addComponent(new StateRenderer('asking_to_play'));
        $this->addComponent(new StateRenderer('game_over'));
        $this->addComponent(new StateRenderer('playing'));
        $this->addComponent(new StateRenderer('preparing'));
        $this->addComponent(new StateRenderer('showing_clue'));
        $this->addComponent(new StateRenderer('success'));
    }
}
