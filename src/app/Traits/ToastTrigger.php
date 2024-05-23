<?php

namespace App\Traits;

trait ToastTrigger
{
    private function _toast(string $message, int $duration = 3000, string $name = 'toast', bool $delayed = false)
    {
        $session_storage = $delayed ? 'delayed_events' : 'events';
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
        $duration = $duration < 1000 ? 3000 : $duration;
        $events = session($session_storage, []);
        $events[] = [
            'name' => 'toast',
            'data' => [
                'toast_name' => $name,
                'duration' => $duration,
                'message' => $message,
            ],
        ];
        session([$session_storage => $events]);
    }

    public function toast(string $message, int $duration = 3000, string $name = 'toast')
    {
        $this->_toast($message, $duration, $name);
    }

    public function delayedToast(string $message, int $duration = 3000, string $name = 'toast')
    {
        $this->_toast($message, $duration, $name, true);
    }
}
