<?php

namespace App\Goblets\Base;

class Component {

    protected bool $enabled = true;

    protected array $attributes = [];

    protected const INTEGER = 'integer';
    protected const STRING = 'string';
    protected const BOOLEAN = 'boolean';

    protected function addAttribute(string $name, mixed $value, string $type = self::INTEGER): void
    {
        $this->attributes[$name] = [
            'value' => $value,
            'type' => $type
        ];
    }

}
