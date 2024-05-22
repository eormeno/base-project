<?php

namespace App\Http\Controllers;

use App\Traits\EventTriggerable;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use EventTriggerable;

    public function pollEvents(Request $request)
    {
        $this->trigger('server_time_changed', now()->toDateTimeString());
        // Retrieve events from the session
        $events = session('events', []);

        // Clear events from session after retrieving them
        session()->forget('events');

        return response()->json($events);
    }
}
