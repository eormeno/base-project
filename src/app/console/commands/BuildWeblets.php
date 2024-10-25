<?php

namespace App\Console\Commands;

use App\Weblets\Collections\WebletsCollection;
use Arr;
use ArrayIterator;
use Illuminate\Console\Command;

class BuildWeblets extends Command
{
    protected $signature = 'make:weblets';

    protected $description = 'Build weblets from configuration.';

    public function handle()
    {
        $this->info('Building weblets...');
        $weblets = $this->getWeblets();
        foreach ($weblets as $key => $weblet) {
            $this->buildWeblet($key, $weblet);
        }
    }

    private function getWeblets(): WebletsCollection
    {
        $weblets = config('weblets');
        return new WebletsCollection($weblets);
    }

    private function buildWeblet(string $prefix, array $weblet)
    {
        $this->info("Building weblet: $prefix {$weblet['title']}");
        foreach ($weblet as $key => $value) {
            if ($key === 'title' || $key === 'root') {
                continue;
            }
            $this->buildComponent($key, $value);
        }
    }

    private function buildComponent(string $component, array $properties)
    {
        $this->info('Building component: ' . $component);
    }

}
