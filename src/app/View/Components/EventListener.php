<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventListener extends Component
{
    public string $event;

    public function __construct(string $event = 'event')
    {
        $this->event = $event;
    }

    public function render(): View|Closure|string
    {
        return view('components.event-listener');
    }
}
