<?php

namespace App\Traits;

trait ToastTrigger
{
    const DEFAULT_TOAST_DURATION = 4000;

    private function _toast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION,
        string $name = 'toast',
        bool $delayed = false
    ) {
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

    public function toast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION,
        string $name = 'toast'
    ) {
        $this->_toast($message, $duration, $name);
    }

    public function warningToast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION
    ) {
        $this->_toast($message, $duration, "warning");
    }

    public function errorToast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION
    ) {
        $this->_toast($message, $duration, "error");
    }

    public function successToast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION
    ) {
        $this->_toast($message, $duration, "success");
    }

    public function infoToast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION
    ) {
        $this->_toast($message, $duration, "info");
    }

    public function delayedToast(
        string $message,
        int $duration = self::DEFAULT_TOAST_DURATION,
        string $name = 'toast'
    ) {
        $this->_toast($message, $duration, $name, true);
    }
}
