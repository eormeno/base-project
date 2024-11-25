<?php

namespace App\Services;

use App\Models\Prefab;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PrefabLoader
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = app_path('prefabs');
    }

    /**
     * Carga recursivamente todos los prefabs del directorio
     *
     * @return array
     */
    public function loadAllPrefabs()
    {
        try {
            if (!File::exists($this->basePath)) {
                File::makeDirectory($this->basePath, 0755, true);
                Log::info("Created prefabs directory at: $this->basePath");
            }
            $prefabsConfig = $this->scanDirectory($this->basePath);
            Log::info("Found " . count($prefabsConfig) . " prefabs in directory: $this->basePath");

            $result = $this->savePrefabs($prefabsConfig);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error loading prefabs: ' . $e->getMessage());
            return [];
        }
    }

    protected function savePrefabs(array $prefabs): array
    {
        $result = ['created' => 0, 'updated' => 0];
        foreach ($prefabs as $name => $prefab) {
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

    // Escanea recursivamente un directorio en busca de documentos php y retorna una lista de nombres de archivos con su ruta relativa al directorio base
    protected function scanDirectory($directory)
    {
        $prefabs = [];
        $files = File::files($directory);
        foreach ($files as $file) {
            $path = $file->getPath() . '/' . $file->getFilename();
            if (File::extension($path) === 'php') {
                $prefab = require $path;
                $prefabs[$this->getPrefabName($path)] = $prefab;
            }
        }
        $directories = File::directories($directory);
        foreach ($directories as $dir) {
            $prefabs = array_merge($prefabs, $this->scanDirectory($dir));
        }
        return $prefabs;
    }

    // Rename the path to the prefab file to the prefab name
    protected function getPrefabName($path)
    {
        $name = str_replace($this->basePath, '', $path);
        $name = str_replace('.php', '', $name);
        // Remove leading slash or backslash
        $name = ltrim($name, '/');
        $name = ltrim($name, '\\');
        // replace slashes or backslashes with dots
        $name = str_replace('/', '.', $name);
        $name = str_replace('\\', '.', $name);
        return $name;
    }



}
