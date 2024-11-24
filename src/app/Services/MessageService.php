<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Contracts\IMessageProvider;

class MessageService
{
    protected $providers = [];

    public function registerProvider(string $component, IMessageProvider $provider): void
    {
        // obtener el shortname de la clase
        $shortName = (new \ReflectionClass($component))->getShortName();
        // remueve la palabra 'Messages' del nombre de la clase
        $component = str_replace('Messages', '', $shortName);
        // registrar el proveedor con el nombre usando Str::slug
        $component = Str::slug($component);
        $this->providers[$component] = $provider;
    }

    public function getMessages(string $component, array $parameters = []): array
    {
        // obtener el shortname de la clase
        $shortName = (new \ReflectionClass($component))->getShortName();
        // remueve la palabra 'Component' del nombre de la clase
        $component = str_replace('Component', '', $shortName);
        // buscar el proveedor registrado para el componente con el nombre usando Str::slug
        $component = Str::slug($component);

        if (!isset($this->providers[$component])) {
            throw new \InvalidArgumentException("No message provider registered for component: {$component}");
        }

        return $this->providers[$component]->getMessages($parameters);
    }
}
