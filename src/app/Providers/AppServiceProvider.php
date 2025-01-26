<?php

namespace App\Providers;

use App\Contracts\IRenderer;
use App\Services\RendererService;
use Illuminate\Support\ServiceProvider;
use App\Models\Prefab\Parsers\GameObjectNameParser;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(IRenderer::class, RendererService::class);
		$this->app->bind(GameObjectNameParser::class, function ($app) {
			return new GameObjectNameParser();
		});
    }

    public function boot(): void
    {
    }
}
