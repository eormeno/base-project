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
            'icon' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon feather feather-activity">
    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
</svg>
SVG,
        ]);
        GameApp::factory()->create([
            'prefix' => 'MTQ',
            'name' => 'Mithic Treasure Quest',
            'description' => 'A game where you explore a world and find treasures using the mechanics of minesweeper.',
            'icon' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon feather feather-activity">
    <circle cx="12" cy="12" r="10"></circle>
    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
    <line x1="19.07" y1="4.93" x2="4.93" y2="19.07"></line>
</svg>
SVG,
        ]);
    }
}
