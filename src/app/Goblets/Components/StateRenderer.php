<?php

namespace App\Goblets\Components;

use App\Goblets\Base\Component;

class StateRenderer extends Component {
    public string $on_state;

    public function __construct(string $on_state)
    {
        $this->on_state = $on_state;
    }
}
