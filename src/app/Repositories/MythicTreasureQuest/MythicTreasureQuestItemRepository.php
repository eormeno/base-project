<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestItem;
use App\Services\AbstractServiceComponent;

class MythicTreasureQuestItemRepository extends AbstractServiceComponent
{
    public function getItemInfo(int $itemId): array
    {
        $item = MythicTreasureQuestItem::findOrFail($itemId);
        $slug = $item->slug;
        $icon = $item->icon;
        $name = $item->name;
        $description = $item->description;
        return compact('slug', 'icon', 'name', 'description');
    }
}
