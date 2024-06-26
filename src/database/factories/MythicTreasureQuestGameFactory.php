<?php

namespace Database\Factories;

use App\Helpers\MapHelper;
use App\Helpers\InventoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MythicTreasureQuestGame>
 */
class MythicTreasureQuestGameFactory extends Factory
{
    public function definition(): array
    {
        $map = MapHelper::generateMap(8, 8);
        $inventory = InventoryHelper::generateInventory();

        return [
            'level' => 1,
            'map' => $map->jsonSerialize(),
            'inventory' => $inventory->jsonSerialize(),
            'health' => 100,
            'is_finished' => false
        ];
    }

}
