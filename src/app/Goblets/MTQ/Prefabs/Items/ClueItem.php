<?php

namespace App\Goblets\MTQ\Prefabs\Items;

use App\Goblets\MTQ\Components\ItemConfig;

class ClueItem extends ItemConfig
{
    public function doAction()
    {
        return $this->name;
    }
}
