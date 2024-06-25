<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MythicTreasureQuestItem>
 */
class MythicTreasureQuestItemFactory extends Factory
{

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->name(),
            'icon' => $this->faker->name(),
            'description' => $this->faker->name()
        ];
    }
}
