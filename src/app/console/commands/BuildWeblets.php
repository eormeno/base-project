<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildWeblets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build-weblets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Building weblets...');
        $weblets = json_encode($this->getWeblets(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->info($weblets);
    }

    private function getWeblets() : array
    {
        return require config_path('weblets.php');
    }
}
