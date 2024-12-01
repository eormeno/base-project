<?php

namespace Database\Seeders;

use App\Models\Game;
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
        GameApp::factory()->image('guess-the-number.jpeg')->create([
            'prefix' => 'gtn',
            'name' => 'Adivina el número',
            'description' => 'Un simple juego donde adivinas un número entre 1 y 1024.',
            'prefab_name' => 'gtn.root'
        ]);
        // Game::factory()->forGameAppPrefix('gtn')->forUserEmail('eormeno@gmail.com')->create();

        GameApp::factory()->image('mythic-treasure-quest.jpeg')->create([
            'prefix' => 'mtq',
            'active' => false,
            'name' => 'Buscador de Tesoros',
            'description' => 'Un juego donde exploras templos antiguos y encuentras tesoros y posiones usando las mecánicas de buscaminas. Pero ten cuidado! También hay trampas, monstruos y maldiciones.'
        ]);
        GameApp::factory()->image('tic-tac-toe.jpeg')->create([
            'prefix' => 'ttt',
            'name' => 'Tic Tac Toe',
            'active' => false,
            'description' => 'Un simple juego de Tic Tac Toe.',
            'max_users_per_instance' => 2,
            'min_users_per_instance' => 2
        ]);
        GameApp::factory()->image('rock-paper-scissors.jpeg')->create([
            'prefix' => 'rps',
            'name' => 'Piedra, Papel, Tijera',
            'active' => false,
            'description' => 'Un simple juego de Piedra, Papel o Tijera.',
            'max_users_per_instance' => 2,
            'min_users_per_instance' => 2
        ]);
    }
}
