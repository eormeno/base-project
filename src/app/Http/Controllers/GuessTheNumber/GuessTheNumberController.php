<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuessTheNumberController extends Controller
{
    protected string $initialStateName = 'asking_to_play';

    public function index(Request $request)
    {
        $this->request();
        return view('guess-the-number')->with($this->game_info);
    }

    public function wantToPlay(Request $request)
    {
        $this->request("want_to_play");
        return redirect()->route('guess-the-number')->with($this->game_info);
    }

    public function reset(Request $request)
    {
        session()->forget('game_info');
        $this->request();
        return redirect()->route('guess-the-number')->with($this->game_info);
    }

    public function guess(Request $request)
    {
        $number = $request->input('number');
        $this->request("guess", $number);
        return redirect()->back()->with($this->game_info);
    }

    public function playAgain(Request $request)
    {
        $this->request("play_again");
        return redirect()->back()->with($this->game_info);
    }

}
