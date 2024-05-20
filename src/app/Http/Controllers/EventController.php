<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function pollEvents(Request $request)
    {
        // Retrieve events from the session
        $events = session('events', []);

        // Clear events from session after retrieving them
        session()->forget('events');

        return response()->json($events);
    }
}
