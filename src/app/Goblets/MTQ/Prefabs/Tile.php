<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\HiddenTileEventListener;
use App\Goblets\MTQ\Components\TileConfig;

class Tile extends GameObject
{
    public string $state = 'hidden';

    public function __construct()
    {
        $this->addComponent(new TileConfig());
        $this->addComponent(new StateRenderer('hidden'));
        $this->addComponent(new StateRenderer('flagged-tile'));
        $this->addComponent(new StateRenderer('flagging-tile'));
        $this->addComponent(new StateRenderer('gameOver-tile'));
        $this->addComponent(new StateRenderer('revealed'));
        $this->addComponent(new HiddenTileEventListener());
    }
}
