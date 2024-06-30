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

}
