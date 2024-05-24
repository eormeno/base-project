<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuessTheNumberController extends Controller
{
    public function index(Request $request)
    {
        $this->request();
        return view('guess-the-number.index')->with($this->info);
    }

    public function wantToPlay(Request $request)
    {
        $this->request("want_to_play");
        return redirect()->route('guess-the-number')->with($this->info);
    }

    public function guess(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|between:' . Globals::MIN_NUMBER . ',' . Globals::MAX_NUMBER
        ]);

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
