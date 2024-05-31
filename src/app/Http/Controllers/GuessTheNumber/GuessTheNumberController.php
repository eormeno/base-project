<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\EventRequestFilter;
use App\Http\Controllers\GuessTheNumber\States\Initial;
use App\Http\Controllers\GuessTheNumber\Services\GameService;
use App\Http\Controllers\GuessTheNumber\Services\GuessService;
use App\Http\Controllers\GuessTheNumber\Services\GameConfigService;
use App\Http\Controllers\GuessTheNumber\Services\GuessTheNumberMessageService;

class GuessTheNumberController extends Controller
{
    public function __construct(
        protected Initial $initial,
        protected UserRepository $userRepository,
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService,
        protected GameService $gameService,
        protected GuessTheNumberMessageService $messageService,
    ) {
    }

    public function getInitialStateClass()
    {
        return $this->initial::class;
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
