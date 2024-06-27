<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestItem;
use App\Services\AbstractServiceComponent;

class MythicTreasureQuestItemRepository extends AbstractServiceComponent
{
    public function getItemInfo(int $itemId): array
    {
        $item = MythicTreasureQuestItem::findOrFail($itemId);
        $icon = $item->icon;
        $name = $item->name;
        $description = $item->description;
        return compact('icon', 'name', 'description');
    }
}
