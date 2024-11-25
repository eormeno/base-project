<?php

namespace App\Providers;

use App\Services\PrefabLoader;
use Illuminate\Support\ServiceProvider;

class PrefabServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PrefabLoader::class, function ($app) {
            return new PrefabLoader();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Solo cargar los prefabs si no estamos en consola o si estamos ejecutando ciertos comandos
        if (!$this->app->runningInConsole() ||
            $this->app->runningUnitTests() ||
            in_array($this->app->request->route()?->getName(), ['prefabs.reload'])) {

            $loader = $this->app->make(PrefabLoader::class);
            $loader->loadAllPrefabs();
        }
    }
}
