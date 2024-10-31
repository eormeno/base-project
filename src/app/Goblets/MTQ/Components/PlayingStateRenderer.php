<?php

namespace App\Goblets\MTQ\Components;

use App\Goblets\MTQ\Prefabs\Map;
use App\Goblets\MTQ\Prefabs\Inventory;
use App\Goblets\Components\StateRenderer;

class PlayingStateRenderer extends StateRenderer
{
    public string $on_state = 'playing';
    public Map $map;
    public Inventory $inventory;

    public function __construct(string $state)
    {
        parent::__construct($state);
        $this->map = new Map();
        $this->inventory = new Inventory();
    }

}
