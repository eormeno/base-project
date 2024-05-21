<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventListener extends Component
{
    public string $event;
    public array $data;

    public function __construct(string $event = 'event', array $data = [])
    {
        $this->event = $event;
        $this->data = $data;
    }

    public function render(): View|Closure|string
    {
        return view('components.event-listener');
    }
}
