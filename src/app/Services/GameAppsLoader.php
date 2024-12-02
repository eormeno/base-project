<?php

namespace App\Services;

use App\Models\Prefab;
use App\Models\GameApp;
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

    protected function updateGameApps(array $gameApps): array
    {
        $result = ['created' => 0, 'updated' => 0];
        foreach ($gameApps as $prefix => $info) {
            $config = $info['config'];
            $config['prefix'] = $prefix;
            $image_name = $config['image'];
            $image_path = $info['resources'][$image_name];
            unset($config['image']);
            $game_app = GameApp::where('prefix', $prefix)->first();
            if ($game_app) {
                $game_app->update($config);
                $result['updated']++;
            } else {
                GameApp::factory()->image($image_path, $image_name)->create($config);
                $result['created']++;
            }
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
