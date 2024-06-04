<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequestFilter;
use App\Http\Controllers\StateContextController;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends StateContextController
{
    public function __construct(GuessTheNumberGameServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->stateStorage = $serviceManager->gameStateStorageService;
    }

    public function index(Request $request)
    {
        $debug = env('APP_DEBUG', false);
        $local_debug = $debug && false;
        return view('guess-the-number.index', ['debug' => $local_debug]);
    }

    public function event(EventRequestFilter $request)
    {
        $event = $request->eventInfo()['event'];
        $data = $request->eventInfo()['data'];
        return $this->request($event, $data)->view();
    }
}
