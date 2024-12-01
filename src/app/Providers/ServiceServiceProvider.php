<?php

namespace App\Providers;

use App\Utils\ClassScanner;
use App\Services\ServiceService;
use App\Contracts\IServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class ServiceServiceProvider extends ServiceProvider
{
    const SERVICE_PROVIDERS_NAMESPACE = 'App\Services';
    const SERVICE_PROVIDERS_DIRECTORY = 'Services';

    public function register()
    {
        $this->app->singleton(ServiceService::class, function ($app) {
            $service = new ServiceService();

            $namespace = self::SERVICE_PROVIDERS_NAMESPACE;
            $directory = app_path(self::SERVICE_PROVIDERS_DIRECTORY);

            // TODO  cache()->forget('service_providers');

            // cache if the app is in production
            $providers = config('app.env') === 'production' ? cache()->rememberForever('service_providers', function () use ($namespace, $directory) {
                return ClassScanner::scanForProviders(IServiceProvider::class, $namespace, $directory);
            }) :
                ClassScanner::scanForProviders(IServiceProvider::class, $namespace, $directory);

            foreach ($providers as $providerClass) {
                $provider = $app->make($providerClass);
                $service->registerProvider($provider);
            }

            return $service;
        });
    }
}
