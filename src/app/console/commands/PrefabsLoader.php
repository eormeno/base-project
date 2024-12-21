<?php

namespace App\Console\Commands;

use App\Models\Prefab;
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
        foreach ($prefabs as $name => $structure) {
            if (!is_array($structure)) {
                $this->command->error("Invalid prefab structure for $name");
                continue;
            }
            // TODO Trabajando en que los prefabs sean herederos de Prefab
            $name = $this->determinePrefabName($prefix, $name);
            $prefab = Prefab::updateOrCreate(['name' => $name], ['structure' => $structure]);
            $this->result[$prefab->wasRecentlyCreated ? 'created' : 'updated']++;
        }
    }

    private function determinePrefabName(string $prefix, string $name): string
    {
        if (strtolower($prefix) === 'common') {
            return $name;
        }
        return "$prefix.$name";
    }
}
