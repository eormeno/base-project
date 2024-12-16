<?php

namespace App\console\commands;

use Illuminate\Console\Command;
use App\Services\GameAppsLoader;

class ReloadGameAppsCommand extends Command
{
    protected $signature = 'game-apps:reload';
    protected $description = 'Reload all games applications configurations';

    public function handle(GameAppsLoader $loader)
    {
        $this->info('Reloading game apps...');
        $game_apps = $loader->loadAllGameApps($this);
        $this->info(json_encode($game_apps, JSON_PRETTY_PRINT));
    }
}
