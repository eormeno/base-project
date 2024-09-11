<?php

namespace App\Services;

use App\Traits\DebugHelper;
use Exception;
use ReflectionClass;
use App\Utils\CaseConverters;

abstract class AbstractServiceManager
{
    use DebugHelper;
    protected $services = [];
    protected ?StateManager $_stateManager = null;
    protected string $baseName = '';
    protected string $baseKebabName = '';

    public function __construct()
    {
        $serviceManagerName = (new ReflectionClass($this))->getShortName();
        $this->baseName = substr($serviceManagerName, 0, -14);
        $this->baseKebabName = CaseConverters::toKebab($this->baseName);
    }

    public function baseName(): string
    {
        return $this->baseName;
    }

    public function baseKebabName(): string
    {
        return $this->baseKebabName;
    }

    protected function addService(string $name, AbstractServiceComponent $service): void
    {
        $this->services[$name] = $service;
    }

    protected function hasService(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        if ($name === 'stateManager') {
            if ($this->_stateManager === null) {
                $this->_stateManager = new StateManager($this);
            }
            return $this->_stateManager;
        }
        throw new Exception("Property $name does not exist");
    }

    public function get(string $name): AbstractServiceComponent
    {
        if (!$this->hasService($name)) {
            throw new Exception("Service $name does not exist");
        }
        return $this->services[$name];
    }
}
