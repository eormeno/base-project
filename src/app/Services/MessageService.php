<?php

namespace App\Services;

use App\Contracts\MessageProviderInterface;

class MessageService
{
    protected $providers = [];

    public function registerProvider(string $component, MessageProviderInterface $provider): void
    {
        $this->providers[$component] = $provider;
    }

    public function getMessages(string $component, array $parameters = []): array
    {
        if (!isset($this->providers[$component])) {
            throw new \InvalidArgumentException("No message provider registered for component: {$component}");
        }

        return $this->providers[$component]->getMessages($parameters);
    }
}
