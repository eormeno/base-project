<?php

namespace App\Goblets\Components;

class StateRenderer {
    public string $on_state;

    public function __construct(string $on_state)
    {
        $this->on_state = $on_state;
    }
}
