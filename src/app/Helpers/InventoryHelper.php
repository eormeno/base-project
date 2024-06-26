<?php

namespace App\Helpers;

use App\Models\MythicTreasureQuestItem;
use App\Models\MythicTreasureQuest\Item;
use App\Models\MythicTreasureQuest\Inventory;

class InventoryHelper
{
    public static function generateInventory(): Inventory
    {
        $defaultItems = MythicTreasureQuestItem::all();
        $inventory = new Inventory();
        $inBagItemId = 1;
        foreach ($defaultItems as $item) {
            $inventory->addItem(new Item($inBagItemId++, $item->id, 1));
        }
        return $inventory;
    }
}
