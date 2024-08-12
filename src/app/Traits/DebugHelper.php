<?php

namespace App\Traits;

trait DebugHelper
{
    const BASIC_INFO = ['source', 'data', 'is_signal', 'rendered'];

    public function log(string $message)
    {
        $events = session('events', []);
        $events[] = [
            'name' => 'log',
            'data' => $message,
        ];
        session(['events' => $events]);
    }

    public function logBacktrace()
    {
        $callers = debug_backtrace();
        array_shift($callers);
        $stack = [];
        foreach ($callers as $i => $caller) {
            if (!isset($caller['file'])) {
                continue;
            }
            if (strpos($caller['file'], 'vendor') !== false) {
                continue;
            }
            $fileName = $caller['file'];
            $fileName = str_replace(base_path(), '', $fileName);
            $stack[] = $fileName . ' (' . $caller['line'] . ')';
        }
        $this->log(json_encode($stack, JSON_PRETTY_PRINT));
    }

    public function logEvent(array $eventInfo, bool $excludeRefresh = true, array $exclude = self::BASIC_INFO)
    {
        if ($excludeRefresh && $eventInfo['event'] === 'refresh') {
            return;
        }
        foreach ($exclude as $key) {
            unset($eventInfo[$key]);
        }
        $this->log(json_encode($eventInfo));
    }

}
