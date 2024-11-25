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
            return $this->scanDirectory($this->basePath);
        } catch (\Exception $e) {
            Log::error('Error loading prefabs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Escanea un directorio recursivamente
     *
     * @param string $directory
     * @param string $prefix
     * @return array
     */
    protected function scanDirectory($directory, $prefix = '')
    {
        $results = [];

        foreach (File::allFiles($directory) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            try {
                // Obtener el path relativo desde la carpeta prefabs
                $relativePath = substr($file->getPath(), strlen($this->basePath) + 1);
                $dotPath = str_replace('/', '.', $relativePath);
                $dotPath = $dotPath ? $dotPath . '.' : '';

                // Obtener el nombre del archivo sin extensión
                $fileName = $file->getFilenameWithoutExtension();

                // Cargar la estructura del archivo
                $prefabStructure = require $file->getRealPath();

                // Crear o actualizar el registro en la base de datos
                $identifier = "$dotPath.$fileName";

                Prefab::updateOrCreate(
                    ['name' => $identifier],
                    ['name' => $identifier, 'structure' => $prefabStructure]
                );

                $results[] = [
                    'identifier' => $identifier,
                    'structure' => $prefabStructure,
                    'path' => $dotPath,
                    'fileName' => $fileName
                ];

                Log::info("Loaded prefab: {$identifier}");
            } catch (\Exception $e) {
                Log::error("Error processing prefab file {$file->getRealPath()} for {$identifier}" . $e->getMessage());
            }
        }

        // Procesar subdirectorios
        foreach (File::directories($directory) as $subDirectory) {
            $dirName = basename($subDirectory);
            $newPrefix = $prefix ? $prefix . '.' . $dirName : $dirName;
            $results = array_merge(
                $results,
                $this->scanDirectory($subDirectory, $newPrefix)
            );
        }

        return $results;
    }
}
