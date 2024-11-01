<?php

namespace App\Goblets\MTQ\Prefabs\Items;

use App\Goblets\MTQ\Components\ItemConfig;

class SelectorItem extends ItemConfig
{
    public function doAction()
    {
        return $this->name;
    }
}
