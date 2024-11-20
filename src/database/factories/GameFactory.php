<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\GameApp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        ];
    }

    public function forGameApp(string $prefix): static
    {
        $gameApp = GameApp::where('prefix', $prefix)->first();
        return $this->state(function (array $attributes) use ($gameApp) {
            return [
                'game_app_id' => $gameApp->id,
                'game_object_id' => $gameApp->prefab->instantiate()->id,
                'invitation_code' => uniqid(),
            ];
        });
    }

    public function forUser(string $email): static
    {
        $user = User::where('email', $email)->first();
        return $this->afterCreating(function ($game) use ($user) {
            $game->players()->attach($user);
        });
    }

}
