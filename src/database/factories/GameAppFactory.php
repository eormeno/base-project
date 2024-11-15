<?php

namespace Database\Factories;

use App\Utils\ImageUtils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Storage;

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
            'active' => true,
            'description' => $this->faker->text
        ];
    }

    public function image(string $image): static
    {
        $seederFolder = "database/seeders/resources/$image";
        if (!file_exists($seederFolder)) {
            throw new \Exception("The image $image does not exist in the seeder folder.");
        }
        $image = "images/$image";
        // if the image exists in the public folder, delete it
        if (!Storage::disk('public')->exists($image)) {
            // copy the image to the public folder of the app
            Storage::disk('public')->put($image, file_get_contents($seederFolder));
        }
        return $this->state(function (array $attributes) use ($image) {
            return [
                'image' => $image,
            ];
        });
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
