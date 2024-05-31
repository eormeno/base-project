<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Http\Controllers\GuessTheNumber\Repositories\GuessTheNumberGameRepository;
use App\Http\Controllers\GuessTheNumber\Services\GameConfigService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\EventRequestFilter;
use App\Http\Controllers\GuessTheNumber\Services\GuessService;

class GuessTheNumberController extends Controller
{
    public function __construct(
        protected Initial $initial,
        protected UserRepository $userRepository,
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService
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
