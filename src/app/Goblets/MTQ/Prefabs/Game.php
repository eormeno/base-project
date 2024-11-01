<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\PlayingStateRenderer;

class Game extends GameObject
{
    public string $sate = 'initial';

    public function __construct()
    {
        $this->addComponent(new StateRenderer('initial'));
        $this->addComponent(new StateRenderer('game_over'));
        $this->addComponent(new PlayingStateRenderer('playing'));
    }
}
