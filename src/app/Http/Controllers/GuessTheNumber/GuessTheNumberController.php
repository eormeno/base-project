<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\States\GuessTheNumber\Initial;
use App\Http\Requests\EventRequestFilter;
use App\Repositories\Globals\UserRepository;
use App\Services\GuessTheNumber\GameService;
use App\Services\GuessTheNumber\GuessService;
use App\Http\Controllers\StateContextController;
use App\Services\GuessTheNumber\GameConfigService;
use App\Services\GuessTheNumber\GuessTheNumberMessageService;

class GuessTheNumberController extends StateContextController
{
    public function __construct(
        protected Initial $initial_state,
        protected UserRepository $userRepository,
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService,
        protected GameService $gameService,
        protected GuessTheNumberMessageService $messageService,
    ) {
        $this->stateStorage = $gameService;
    }

    public function index(Request $request)
    {
        try {
            $debug = env('APP_DEBUG', false);
            $local_debug = $debug && true;
            return view('guess-the-number.index', ['debug' => $local_debug]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function event(EventRequestFilter $request)
    {
        try {
            $event = $request->eventInfo()['event'];
            $data = $request->eventInfo()['data'];
            return $this->request($event, $data)->view();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
