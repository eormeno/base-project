<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameApp>
 */
class GameAppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $threeLetterCode = $this->faker->unique()->regexify('[A-Z]{3}');
        $fakeSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon feather feather-activity">
  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
</svg>
SVG;
        return [
            'prefix' => $threeLetterCode,
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'icon' => $fakeSvg,
        ];
    }
}
