<?php

namespace App\Traits;

trait ToastTrigger
{
    public function toast(string $message, int $duration = 3000, string $name = 'toast')
    {
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
        $duration = $duration < 1000 ? 3000 : $duration;
        $events = session('events', []);
        $events[] = [
            'name' => 'toast',
            'data' => [
                'toast_name' => $name,
                'duration' => $duration,
                'message' => $message,
            ],
        ];
        session(['events' => $events]);
    }
}
