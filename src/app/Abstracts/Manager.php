<?php

namespace App\Abstracts;

abstract class Manager
{
    private $components = [];

    public function addComponent($component)
    {
        $this->components[] = $component;
    }

    public function __call($name, $arguments)
    {
        foreach ($this->components as $component) {
            if (method_exists($component, $name)) {
                return $component->$name(...$arguments);
            }
        }
    }
}
