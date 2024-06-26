<?php

namespace App\States\Inventory;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryDisplaying extends StateAbstractImpl
{
    protected function cast(): Inventory
    {
        return $this->model;
    }
}
