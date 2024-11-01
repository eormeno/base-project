<?php

namespace App\Goblets\MTQ\Prefabs\Items;

use App\Goblets\Base\GameObject;
use App\Goblets\Components\StateRenderer;
use App\Goblets\MTQ\Components\ItemConfig;

class SelectorItem extends GameObject
{

    public string $state = 'initial';

    public function __construct()
    {
        $this->addComponent(new ItemConfig('selector', '🔍', 'Selector', 1));
        $this->addComponent(new StateRenderer('initial'));
    }

}
