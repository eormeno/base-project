<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuessTheNumberGame>
 */
class GuessTheNumberGameFactory extends Factory
{

    public function definition(): array
    {
        return [
            'state' => null,
            'min_number' => 1,
            'max_number' => 100,
            'max_attempts' => 10,
            'half_attempts' => 5,
            'remaining_attempts' => 10,
        ];
    }
}
