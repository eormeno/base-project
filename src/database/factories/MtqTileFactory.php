<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MtqTileFactory extends Factory
{
    public function definition()
    {
        return [
            'state' => null,
            'started_at' => null,
            'has_trap' => false,
            'has_flag' => false,
            'marked_as_clue' => false,
            'traps_around' => 0,
        ];
    }
}
