<?php

namespace App\Console\Commands;

use App\Services\PrefabLoader;
use Illuminate\Console\Command;

class ReloadPrefabsCommand extends Command
{
    protected $signature = 'prefabs:reload';
    protected $description = 'Reload all prefab configurations';

    public function handle(PrefabLoader $loader)
    {
        $this->info('Reloading prefabs...');
        $prefabs = $loader->loadAllPrefabs();
        $this->info('Loaded ' . count($prefabs) . ' prefabs successfully.');
    }
}
