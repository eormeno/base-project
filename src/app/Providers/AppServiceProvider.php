<?php

namespace App\Providers;

use App\Contracts\IRenderer;
use App\Services\RendererService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(IRenderer::class, RendererService::class);
    }

    public function boot(): void
    {
    }
}
