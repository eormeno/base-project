<?php

namespace App\Providers;

use ReflectionClass;
use App\Utils\ClassScanner;
use App\Services\MessageService;
use App\Contracts\IMessageProvider;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    const MESSAGE_PROVIDERS_NAMESPACE = 'App\Models\Components';
    const MESSAGE_PROVIDERS_DIRECTORY = 'Models\Components';

    public function register()
    {
        $this->app->singleton(MessageService::class, function ($app) {
            $service = new MessageService();

            $namespace = self::MESSAGE_PROVIDERS_NAMESPACE;
            $directory = app_path(self::MESSAGE_PROVIDERS_DIRECTORY);

            // TODO  cache()->forget('message_providers');

            $providers = cache()->rememberForever('message_providers', function () use ($namespace, $directory) {
                return ClassScanner::scanForProviders(IMessageProvider::class, $namespace, $directory);
            });

            foreach ($providers as $providerClass) {
                // Instanciar la clase y registrar
                $provider = $app->make($providerClass);
                // obtener el nombre completo de la clase en string
                $className = (new ReflectionClass($provider))->getName();
                $service->registerProvider($className, $provider);
            }

            return $service;
        });
    }
}
