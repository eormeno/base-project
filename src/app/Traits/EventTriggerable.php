<?php

namespace App\Traits;

trait EventTriggerable
{
    public function trigger(string $event_name, array $event_data = [])
    {
        $events = session('events', []);
        $events[] = [
            'name' => $event_name,
            'data' => $event_data,
        ];
        session(['events' => $events]);
    }
}
