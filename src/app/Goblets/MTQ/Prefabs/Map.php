<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\MapConfig;

class Map extends GameObject
{
    public string $state = 'initial';

    public function __construct()
    {
        $this->addComponent(new MapConfig(8, 8, 8));
        $this->addComponent(new StateRenderer('initial'));
    }
}
