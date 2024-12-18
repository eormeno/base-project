<?php

namespace App\Services;

use App\Models\Prefab;
use App\Models\GameApp;
use App\Utils\CaseConverters;
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

    public function loadAllGameApps(Command $command): array
    {
        $this->command = $command;
        $tree = $this->buildTree($this->basePath);
        $result = $this->updateGameApps($tree);
        return $result;
    }

    private function buildTree($directory)
    {
        $result = [];
        if (!is_dir($directory)) {
            return $result;
        }
        $items = File::files($directory);
        foreach ($items as $file) {
            $extension = $file->getExtension();
            $fileName = $file->getFilename();
            if ($extension === 'php') {
                $fileName = $this->nameToSlug($file->getFilename());
                if ($this->isPhpFileAClass($file->getPathname())) {
                    $class_name = Str::before($file->getPathname(), '.php');
                    $class_name = 'App' . Str::after($class_name, app_path());
                    $class_name = str_replace('/', '\\', $class_name);
                    $result[$fileName] = $class_name;
                    continue;
                }
                $fileContent = include $file->getPathname();
                $result[$fileName] = $fileContent;
            } elseif ($extension === 'jpeg' || $extension === 'jpg') {
                $result[$fileName] = $file->getPath();
            }
        }
        $subdirectories = File::directories($directory);
        foreach ($subdirectories as $subdirectory) {
            $name = basename($subdirectory);
            $result[$name] = $this->buildTree($subdirectory);
        }
        return $result;
    }

    private function isPhpFileAClass($file): bool
    {
        $content = file_get_contents($file);
        $tokens = token_get_all($content);
        $class_token = false;
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $class_token = true;
            }
            if ($class_token && $token[0] === T_STRING) {
                return true;
            }
        }
        return false;
    }

    protected function updateGameApps(array $gameApps): array
    {
        $result = ['apps_created' => 0, 'apps_updated' => 0, 'prefabs_created' => 0, 'prefabs_updated' => 0];
        foreach ($gameApps as $prefix => $info) {
            if (!isset($info['config'])) {
                continue;
            }
            $config = $info['config'];
            $config['prefix'] = $prefix;
            $image_name = $config['image'];
            $image_path = $info['resources'][$image_name];
            unset($config['image']);
            $config['service_registry'] = isset($info['Services']) ? $info['Services'] : [];
            $game_app = GameApp::where('prefix', $prefix)->first();
            if ($game_app) {
                $game_app->update($config);
                $result['apps_updated']++;
            } else {
                GameApp::factory()->image($image_path, $image_name)->create($config);
                $result['apps_created']++;
            }
            if (isset($info['prefabs'])) {
                $result_prefabs = $this->updatePrefabs($prefix, $info['prefabs']);
                $result['prefabs_created'] += $result_prefabs['created'];
                $result['prefabs_updated'] += $result_prefabs['updated'];
            }
        }
        return $result;
    }

    protected function updatePrefabs(string $prefix, array $prefabs): array
    {
        $result = ['created' => 0, 'updated' => 0];
        foreach ($prefabs as $name => $prefab) {
            $name = "$prefix.$name";
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

    protected function nameToSlug(string $name): string
    {
        return CaseConverters::camelToKebab(preg_replace('/\.[^.]*$/', '', $name));
    }
}
