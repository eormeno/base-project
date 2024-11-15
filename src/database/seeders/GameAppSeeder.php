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
        GameApp::factory()->image('guess-the-number.jpeg')->create([
            'prefix' => 'GTN',
            'name' => 'Adivina el número',
            'description' => 'Un simple juego donde adivinas un número entre 1 y 1024.',
        ]);
        GameApp::factory()->image('mythic-treasure-quest.jpeg')->create([
            'prefix' => 'MTQ',
            'active' => false,
            'name' => 'Buscador de Tesoros',
            'description' => 'Un juego donde exploras templos antiguos y encuentras tesoros y posiones usando las mecánicas de buscaminas. Pero ten cuidado! También hay trampas, monstruos y maldiciones.'
        ]);
        GameApp::factory()->image('tic-tac-toe.jpeg')->create([
            'prefix' => 'TTT',
            'name' => 'Tic Tac Toe',
            'active' => false,
            'description' => 'Un simple juego de Tic Tac Toe.'
        ]);
        GameApp::factory()->image('rock-paper-scissors.jpeg')->create([
            'prefix' => 'RPS',
            'name' => 'Piedra, Papel, Tijera',
            'active' => false,
            'description' => 'Un simple juego de Piedra, Papel o Tijera.'
        ]);
    }
}
