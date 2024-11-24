<?php

namespace App\Providers;

use App\Services\MessageService;
use Illuminate\Support\ServiceProvider;
use App\Models\Components\GTN\Messages\AskingToPlayStateMessages;

class MessageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MessageService::class, function ($app) {
            $service = new MessageService();

            // Registrar los proveedores de mensajes
            $service->registerProvider('asking-to-play', $app->make(AskingToPlayStateMessages::class));

            return $service;
        });
    }
}
