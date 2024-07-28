<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MtqGameItemFactory extends Factory
{
    public function definition()
    {
        return [
            'state' => null,
            'entered_at' => null,
        ];
    }

    public function quantity(int $quantity)
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
        ]);
    }
}
