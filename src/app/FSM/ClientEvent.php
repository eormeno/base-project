<?php

namespace App\FSM;

class ClientEvent extends Event
{
    protected array $rendered = [];

    public function __construct(string $name, string $destination, array $data, string $source, array $rendered, bool $isSignal = false)
    {
        parent::__construct($name, $destination, $data, $source, $isSignal);
        $this->rendered = $rendered;
    }

    public function rendered(): array
    {
        return $this->rendered;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), ['rendered' => $this->rendered]);
    }
}
