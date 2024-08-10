<?php

namespace App\FSM;

class Signal extends Event
{
    public function __construct(string $name, string $destination, array $data, string $source)
    {
        parent::__construct($name, $destination, $data, $source, true);
    }
}
