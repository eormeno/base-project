<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrefabSeeder extends Seeder
{
    public function run(): void
    {
        $prefabLoader = app(\App\Services\PrefabLoader::class);
        $prefabLoader->loadAllPrefabs();
    }
}
