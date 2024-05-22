<?php

namespace App\Http\Controllers;

use App\Traits\EventTriggerable;
use App\Traits\ToastTrigger;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use EventTriggerable;
    use ToastTrigger;

    public function triggerEvent()
    {
        $this->toast('¡Game Over!', 5000, 'error');
        $this->toast('You won!');
        $this->toast('Better luck next time!', 10000, 'warning');
        return response()->json();
    }

    public function pollEvents(Request $request)
    {
        $this->trigger('server_time_changed', now()->toDateTimeString());
        $events = session('events', []);
        session()->forget('events');
        return response()->json($events);
    }
}
