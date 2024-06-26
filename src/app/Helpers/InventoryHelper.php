<?php

namespace App\Helpers;

use App\Models\MythicTreasureQuest\Bag;
use App\Models\MythicTreasureQuest\Item;
use App\Models\MythicTreasureQuestItem;

class InventoryHelper
{
    public static function generateInventory(): Bag
    {
        $defaultItems = MythicTreasureQuestItem::all();
        $bag = new Bag();
        $inBagItemId = 1;
        foreach ($defaultItems as $item) {
            $bag->addItem(new Item($inBagItemId++, $item->id, 1));
        }
        return $bag;
    }
}
