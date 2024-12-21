<?php

namespace App\Console\Commands;

use App\Models\Prefab;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class PrefabsLoader
{
    protected Command $command;
    private array $result = ['created' => 0, 'updated' => 0];


    public function load(array $fileTree, Command $command): array
    {
        $this->command = $command;
        $this->updateOrCreatePrefabs($fileTree);
        return array_filter($this->result);
    }

    protected function updateOrCreatePrefabs(array $gameApps): void
    {
        foreach ($gameApps as $folder => $element) {
            if ($prefabs = $element['prefabs'] ?? null) {
                $this->updatePrefabs($folder, $prefabs);
            }
        }
    }

    protected function updatePrefabs(string|null $prefix, array $prefabs): void
    {
        foreach ($prefabs as $name => $prefab) {
            // TODO Trabajando en que los prefabs sean herederos de Prefab
            if (Str::lower($prefix) === 'common') {
                $prefix = null;
            }
            $name = $prefix ? "$prefix.$name" : $name;
            $p = Prefab::find($name);
            if ($p) {
                $p->update(['structure' => $prefab]);
                $this->result['updated']++;
            } else {
                $p = Prefab::create(['name' => $name, 'structure' => $prefab]);
                $this->result['created']++;
            }
        }
    }
}
