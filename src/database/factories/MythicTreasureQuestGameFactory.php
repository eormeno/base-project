<?php

namespace Database\Factories;

use App\Helpers\MapHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MythicTreasureQuestGame>
 */
class MythicTreasureQuestGameFactory extends Factory
{
    public function definition(): array
    {
        $map = MapHelper::generateMap(8, 8);

        return [
            'level' => 1,
            'map' => $map->jsonSerialize(),
            'health' => 100,
            'is_finished' => false
        ];
    }

}
