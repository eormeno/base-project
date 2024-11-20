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
            'state' => 'initial',
            'components' => [
                'gtn.game-data' => [
                    'min_number' => 1,
                    'max_number' => 1024,
                    'attempts' => 0,
                    'max_attempts' => 10,
                    'score' => 0,
                ],
                'gtn.asking-to-play' => [],
            ]
        ];

        Prefab::create([
            'name' => 'Guess The Number',
            'structure' => $guessTheNumberPrefabStructure
        ]);
    }
}
