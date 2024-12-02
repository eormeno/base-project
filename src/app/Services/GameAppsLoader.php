<?php

namespace App\Services;

use App\Models\Prefab;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GameAppsLoader
{
    protected $basePath;
    protected Command $command;

    public function __construct()
    {
        $this->basePath = app_path('GameApps');
    }

    /**
     * Carga recursivamente todos las aplicaciones de juego del directorio
     *
     * @return array
     */
    public function loadAllGameApps(Command $command): array
    {
        $this->command = $command;
        $result = ['created' => 0, 'updated' => 0];
        $tree = $this->buildTree($this->basePath);
        $this->command->info(json_encode($tree, JSON_PRETTY_PRINT));
        return $result;
    }

    private function buildTree($directory)
    {
        $result = [];

        if (!is_dir($directory)) {
            return $result;
        }

        // Procesa archivos en la carpeta actual
        $items = File::files($directory);
        foreach ($items as $file) {
            $extension = $file->getExtension();
            $fileName = $this->nameToSlug($file->getFilename());

            if ($extension === 'php') {
                // Ejecuta el archivo PHP y guarda el resultado
                $fileContent = include $file->getPathname();
                $result[$fileName] = $fileContent;
            } elseif ($extension === 'jpeg' || $extension === 'jpg') {
                $result[$fileName] = $file->getPathname();
            }
        }

        // Procesa subdirectorios recursivamente
        $subdirectories = File::directories($directory);
        foreach ($subdirectories as $subdirectory) {
            $name = basename($subdirectory);
            $result[$name] = $this->buildTree($subdirectory);
        }

        return $result;
    }

    protected function saveGameApps(array $gameApps): array
    {
        $result = ['created' => 0, 'updated' => 0];
        foreach ($gameApps as $name => $prefab) {
            $p = Prefab::find($name);
            if ($p) {
                $p->update(['structure' => $prefab]);
                $result['updated']++;
            } else {
                $p = Prefab::create(['name' => $name, 'structure' => $prefab]);
                $result['created']++;
            }
        }
        return $result;
    }

    protected function nameToSlug(string $name) : string
    {
        return Str::slug(preg_replace('/\.[^.]*$/', '', $name));
    }
}
