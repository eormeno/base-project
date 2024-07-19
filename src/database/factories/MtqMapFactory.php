<?php

namespace Database\Factories;

use App\Models\MtqMap;
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
            $tiles = [];
            for ($i = 0; $i < $map->width; $i++) {
                for ($j = 0; $j < $map->height; $j++) {
                    $tiles[] = [
                        'x' => $i,
                        'y' => $j,
                        'state' => null,
                        'has_trap' => false,
                        'has_flag' => false,
                        'marked_as_clue' => false,
                        'traps_around' => 0,
                    ];
                }
            }
            $map->tiles()->createMany($tiles);
        });
    }
}
