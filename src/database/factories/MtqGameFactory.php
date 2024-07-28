<?php

namespace Database\Factories;

use App\Models\MtqInventory;
use App\Models\MtqMap;
use App\Models\MtqGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MtqGame>
 */
class MtqGameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'state' => null,
            'entered_at' => null,
        ];
    }

    public function configure(): MtqGameFactory
    {
        return $this->afterCreating(function (MtqGame $mtqGame) {
            MtqMap::factory()->for($mtqGame)->create();
            MtqInventory::factory()->for($mtqGame)->create();
        });
    }
}
