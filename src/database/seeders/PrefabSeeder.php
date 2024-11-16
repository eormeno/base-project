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
            'components' => [
                'type'=> 'guess-the-number-data',
                'properties' => [
                    'min' => 1,
                    'max' => 1024,
                    'attempts' => 10
                ]
            ]
        ];

        Prefab::create([
            'name' => 'Guess The Number',
            'structure' => $guessTheNumberPrefabStructure
        ]);
    }
}
