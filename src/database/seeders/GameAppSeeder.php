<?php

namespace Database\Seeders;

use App\Models\GameApp;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameApp::factory()->create([
            'prefix' => 'GTN',
            'name' => 'Guess The Number',
            'description' => 'A simple game where you guess a number between 1 and 1024.',
            'image' => 'images/guess-the-number.jpeg',
        ]);
        GameApp::factory()->create([
            'prefix' => 'MTQ',
            'name' => 'Mithic Treasure Quest',
            'description' => 'A game where you explore a world and find treasures using the mechanics of minesweeper.',
            'image' => 'images/mythic-treasure-quest.jpeg',
        ]);
        GameApp::factory()->fakeImage()->create([
            'name' => 'Tic Tac Toe',
            'description' => 'A simple game of Tic Tac Toe.'
        ]);
    }
}
