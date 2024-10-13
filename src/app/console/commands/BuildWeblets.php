<?php

namespace App\Console\Commands;

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
        foreach ($weblets as $weblet) {
            $this->buildWeblet($weblet);
        }
    }

    private function getWeblets(): ArrayIterator
    {
        $weblets = config('weblets');
        return new ArrayIterator($weblets);
    }

    private function buildWeblet(array $weblet)
    {
        $this->info('Building weblet: ' . $weblet['title']);
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
