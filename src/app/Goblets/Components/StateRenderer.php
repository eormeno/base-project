<?php

namespace App\Goblets\Components;

use App\Goblets\Base\Component;
use View;

class StateRenderer extends Component {
    public string $on_state;

    public function __construct(string $on_state)
    {
        $this->on_state = $on_state;
        $this->enabled = false;
    }

    public function render()
    {
        return view('goblets.components.state-renderer', [
            'on_state' => $this->on_state
        ]);
    }
}
