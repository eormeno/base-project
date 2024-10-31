<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\MapConfig;

class Map
{
    public string $state = 'initial';
    public array $components = [];

    public function __construct()
    {
        $this->components[] = new MapConfig(8, 8, 8);
        $this->components[] = new StateRenderer('initial');
    }
}
