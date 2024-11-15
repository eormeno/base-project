<?php

namespace Database\Factories;

use App\Utils\ImageUtils;
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
        return [
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->text
        ];
    }

    // a factory with a fake image
    public function fakeImage(): static
    {
        return $this->state(function (array $attributes) {
            $threeLetterCode = $this->faker->unique()->regexify('[A-Z]{3}');
            $fakeImage = ImageUtils::saveImage(640, 480, "images/$threeLetterCode-fake.jpg");
            return [
                'prefix' => $threeLetterCode,
                'image' => $fakeImage,
            ];
        });
    }
}
