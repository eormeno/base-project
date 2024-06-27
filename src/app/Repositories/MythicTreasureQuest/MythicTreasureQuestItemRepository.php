<?php

namespace App\Repositories\MythicTreasureQuest;

use Exception;
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

    public function getItemInfoBySlug(string $slug): array
    {
        try {
            $item = MythicTreasureQuestItem::where('slug', $slug)->firstOrFail();
            $id = $item->id;
            $icon = $item->icon;
            $name = $item->name;
            $description = $item->description;
            return compact('id', 'icon', 'name', 'description');
        } catch (Exception $e) {
            return [];
        }
    }
}
