<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MtqGame;
use App\Models\MtqItemClass;
use Illuminate\Database\Seeder;
use App\Models\GuessTheNumberGame;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10);

        $items = [
            ['slug' => 'selector', 'name' => 'Selector', 'icon' => 'selector.svg', 'description' => 'Selection tool', 'default_quantity' => 1],
            ['slug' => 'clue', 'name' => 'Clue', 'icon' => 'clue.svg', 'description' => 'Hint to the next location', 'default_quantity' => 2],
            ['slug' => 'flag', 'name' => 'Flag', 'icon' => 'flag.svg', 'description' => 'Marking tool', 'default_quantity' => 8],
        ];

        MtqItemClass::factory()->createMany($items);

        User::factory()->adminUser()
            ->has(GuessTheNumberGame::factory())
            ->has(MtqGame::factory())
            ->create();

        //User::factory()->has(GuessTheNumberGame::factory()->fakeGame())->count(9)->create();

    }
}
