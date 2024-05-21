<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Traits\EventTriggerable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuessTheNumberController extends Controller
{
    use EventTriggerable;

    public function triggerEvent()
    {
        $this->trigger('state_changed', 'playing');
        return response()->json(); // returns an empty json response
    }

    public function index(Request $request)
    {
        $this->request();
        return view('guess-the-number')->with($this->info);
    }

    public function wantToPlay(Request $request)
    {
        $this->request("want_to_play");
        return redirect()->route('guess-the-number')->with($this->info);
    }

    public function reset(Request $request)
    {
        session()->forget('info');
        $this->request();
        return redirect()->route('guess-the-number')->with($this->info);
    }

    public function guess(Request $request)
    {
        $number = $request->input('number');
        $this->request("guess", $number);
        return redirect()->back()->with($this->info);
    }

    public function playAgain(Request $request)
    {
        $this->request("play_again");
        return redirect()->back()->with($this->info);
    }

}
