<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\GuessTheNumberGame;
use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuestItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10);

        MythicTreasureQuestItem::factory([
            'slug' => 'selector',
            'name' => 'Selector',
            'icon' => 'selector.png',
            'description' => 'Selection tool'
        ])->create();

        MythicTreasureQuestItem::factory([
            'slug' => 'flag',
            'name' => 'Flag',
            'icon' => 'flag.png',
            'description' => 'Marking tool'
        ])->create();

        MythicTreasureQuestItem::factory([
            'slug' => 'clue',
            'name' => 'Clue',
            'icon' => 'clue.png',
            'description' => 'Hint to the next location'
        ])->create();

        User::factory()->adminUser()
            ->has(GuessTheNumberGame::factory())
            ->has(MythicTreasureQuestGame::factory())
            ->create();

        User::factory()->has(GuessTheNumberGame::factory()->fakeGame())->count(9)->create();

    }
}
