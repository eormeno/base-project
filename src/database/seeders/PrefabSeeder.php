<?php

namespace Database\Seeders;

use App\Models\Prefab;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrefabSeeder extends Seeder
{
    public function run(): void
    {
        $guessTheNumberPrefabStructure = [
            'name' => 'root',
            'state' => 'asking-to-play',
            'components' => [
                'gtn.game-data' => [
                    'min_number' => 1,
                    'max_number' => 1024,
                    'attempts' => 0,
                    'max_attempts' => 10,
                    'score' => 0,
                ],
                'gtn.initial-state' => [],
                'gtn.asking-to-play-state' => [],
                'gtn.game-over-state' => [],
                'gtn.playing-state' =>[],
                'gtn.preparing-state' => [],
                'gtn.showing-clue-state' => [],
                'gtn.success-state' => [],
            ]
        ];

        Prefab::create([
            'name' => 'Guess The Number',
            'structure' => $guessTheNumberPrefabStructure
        ]);
    }
}
