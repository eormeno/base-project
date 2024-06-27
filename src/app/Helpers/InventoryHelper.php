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

        $selector = $defaultItems->where('slug', 'selector')->first();
        $flag = $defaultItems->where('slug', 'flag')->first();
        $clue = $defaultItems->where('slug', 'clue')->first();

        $inventory = new Inventory();
        $inventory->addItem(new Item(1, $selector->id, -1));
        $inventory->addItem(new Item(2, $flag->id, 8));
        $inventory->addItem(new Item(3, $clue->id, 2));

        return $inventory;
    }
}
