<?php

namespace Database\Seeders;

use App\Services\PrefabLoader;
use Illuminate\Database\Seeder;

class PrefabSeeder extends Seeder
{
    public function run(): void
    {
        $prefabLoader = app(PrefabLoader::class);
        $prefabLoader->loadAllPrefabs();
    }
}
