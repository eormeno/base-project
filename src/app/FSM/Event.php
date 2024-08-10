<?php

namespace App\FSM;

use ReflectionClass;

class Event
{
    protected string $name;
    protected string $destination;
    protected string $source;
    protected bool $isSignal = false;
    protected array $data = [];

    public function __construct(string $name, string $destination, array $data, string $source, bool $isSignal = false)
    {
        $this->name = $name;
        $this->destination = $destination;
        $this->data = $data;
        $this->source = $source;
        $this->isSignal = $isSignal;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function destination(): string
    {
        return $this->destination;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function isSignal(): bool
    {
        return $this->isSignal;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function serialize(): array
    {
        return [
            'event' => $this->name,
            'destination' => $this->destination,
            'source' => $this->source,
            'is_signal' => $this->isSignal,
        ];
    }

    public static function EventClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }
}
