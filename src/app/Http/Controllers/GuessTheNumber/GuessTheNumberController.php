<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequestFilter;
use App\Repositories\CurrentUserRepository;
use App\Http\Controllers\GuessTheNumber\Services\GuessService;

class GuessTheNumberController extends Controller
{
    protected $guessService;

    public function __construct(CurrentUserRepository $currentUserRepository, GuessService $guessService)
    {
        $this->user_name = $currentUserRepository->name();
        $this->guessService = $guessService;
    }

    public function getInitialStateClass()
    {
        return Initial::class;
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
