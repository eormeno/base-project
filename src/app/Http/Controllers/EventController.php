<?php

namespace App\Http\Controllers;

use App\Traits\ToastTrigger;
use Illuminate\Http\Request;
use App\Traits\EventTriggerable;

class EventController extends Controller
{
    use EventTriggerable;
    use ToastTrigger;

    public function triggerEvent()
    {
        $this->delayedToast('¡Game Over!', 5000, 'error');
        $this->toast('You won!', 3000, 'success');
        $this->delayedToast('Better luck next time!', 10000, 'warning');
        return response()->json();
    }

    public function pollEvents(Request $request)
    {
        $reloaded = $request->input('reloaded', false);
        $session_storage = $reloaded ? 'delayed_events' : 'events';

        $this->trigger('server_time_changed', now()->toDateTimeString());
        $events = session($session_storage, []);
        session()->forget($session_storage);
        return response()->json($events);
    }
}
