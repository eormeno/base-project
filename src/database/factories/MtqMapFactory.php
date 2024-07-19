<?php

namespace Database\Factories;

use App\Models\MtqMap;
use App\Helpers\MapHelper2;
use Illuminate\Database\Eloquent\Factories\Factory;

class MtqMapFactory extends Factory
{
    public function definition()
    {
        return [
            'width' => 8,
            'height' => 8,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (MtqMap $map) {
            $tiles = MapHelper2::generateMap($map->width, $map->height, 8);
            $map->tiles()->createMany($tiles);
        });
    }
}
