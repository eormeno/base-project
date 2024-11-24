<?php

namespace App\Providers;

use App\Utils\ClassScanner;
use App\Services\MessageService;
use App\Contracts\IMessageProvider;
use Illuminate\Support\ServiceProvider;
use App\Models\Components\GTN\Messages\AskingToPlayStateMessages;

class MessageServiceProvider extends ServiceProvider
{
    public function registerx()
    {
        $this->app->singleton(MessageService::class, function ($app) {
            $service = new MessageService();

            // Registrar los proveedores de mensajes
            $service->registerProvider('asking-to-play', $app->make(AskingToPlayStateMessages::class));

            return $service;
        });
    }

    public function register()
    {
        $this->app->singleton(MessageService::class, function ($app) {
            $service = new MessageService();

            // Directorio y namespace donde están los proveedores
            $namespace = 'App\Models\Components';
            $directory = app_path('Models\Components');

            // Buscar todas las clases que implementen MessageProviderInterface
            //$providers = ClassScanner::scanForProviders(IMessageProvider::class, $namespace, $directory);

            // cache()->forget('message_providers');

            $providers = cache()->rememberForever('message_providers', function () use ($namespace, $directory) {
                return ClassScanner::scanForProviders(IMessageProvider::class,$namespace, $directory);
            });

            foreach ($providers as $providerClass) {
                // Instanciar la clase y registrar
                $provider = $app->make($providerClass);
                // obtener el nombre completo de la clase en string
                $className = (new \ReflectionClass($provider))->getName();
                $service->registerProvider($className, $provider);
            }

            return $service;
        });
    }
}
