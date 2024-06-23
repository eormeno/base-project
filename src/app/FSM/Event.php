<?php

namespace App\FSM;

use ReflectionClass;

class Event
{
    protected string $name;
    protected string $destination;
    protected string $source;

    public function __construct(string $name, string $destination, string $source)
    {
        $this->name = $name;
        $this->destination = $destination;
        $this->source = $source;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function serialize(): array
    {
        return [
            'event' => $this->name,
            'destination' => $this->destination,
            'source' => $this->source,
        ];
    }

    public static function EventClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }
}
