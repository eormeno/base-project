<?php

namespace App\Traits;

trait DebugHelper
{
    public function log(string $message)
    {
        $events = session('events', []);
        $events[] = [
            'name' => 'log',
            'data' => $message,
        ];
        session(['events' => $events]);
    }
}
