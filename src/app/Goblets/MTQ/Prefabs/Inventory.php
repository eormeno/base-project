<?php

namespace App\Goblets\MTQ\Prefabs;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\InventoryConfig;

class Inventory extends GameObject
{
    public string $state = 'initial';

    public function __construct()
    {
        $this->addComponent(new InventoryConfig());
        $this->addComponent(new StateRenderer('initial'));
    }
}
