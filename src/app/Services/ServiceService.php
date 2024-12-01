<?php

namespace App\Services;

use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Utils\ReflectionUtils;
use App\Contracts\IServiceProvider;

class ServiceService
{
    protected $providers = [];

    public function registerProvider(IServiceProvider $provider): void
    {
        $shortName = ReflectionUtils::short($provider);
        $shortName = str_replace('Service', '', $shortName);
        $slugName = Str::slug($shortName);
        $this->providers[$slugName] = $provider;
    }

    public function getService(string $slug_name): IServiceProvider
    {
        if (!isset($this->providers[$slug_name])) {
            throw new InvalidArgumentException("No service provider registered for service: {$slug_name}");
        }
        return $this->providers[$slug_name];
    }
}
