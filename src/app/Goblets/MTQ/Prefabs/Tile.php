<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Base\GameObject;

class Tile extends GameObject
{
    public function __construct()
    {
        $this->addComponent(new TileConfig());
    }
}
