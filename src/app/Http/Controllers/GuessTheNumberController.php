<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuessTheNumberController extends Controller
{

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
