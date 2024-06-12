<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MythicTreasureQuestGame>
 */
class MythicTreasureQuestGameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level' => 1,
            'health' => 100,
            'map' => '{}',
            'inventory' => '{}',
            'is_finished' => false
        ];
    }

    public function fakeGame(): static
    {
        return $this->state(
            function (array $attributes) {

                return [
                    'level' => random_int(1, 10),
                    'health' => random_int(1, 100),
                    'is_finished' => rand(0, 1) == 1
                ];
            }
        )->sequence(
                ['state' => 'initial'],
                ['state' => 'playing'],
                ['state' => 'success'],
                ['state' => 'game_over']
            );
    }

}
