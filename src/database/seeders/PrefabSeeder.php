<?php

namespace Database\Seeders;

use App\Models\Prefab;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrefabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guessTheNumberPrefabStructure = [
            'name' => 'root',
            'state' => 'initial',
            'components' => [
                [
                    'type' => 'gtn.game-data',
                    'properties' => [
                        'min' => 1,
                        'max' => 1024,
                        'attempts' => 10
                    ]
                ],
                [
                    'type' => 'state-web-renderer',
                    'properties' => [
                        'rendered_state' => 'initial',
                        'slot' => 'main',
                    ]
                ]
            ]
        ];

        Prefab::create([
            'name' => 'Guess The Number',
            'structure' => $guessTheNumberPrefabStructure
        ]);
    }
}
