<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuessTheNumberController extends Controller
{
    public function getInitialStateClass() {
        return Initial::class;
    }

    public function index(Request $request)
    {
        $view_name = $this->request()::name();
        return view("guess-the-number.$view_name")->with($this->info);
    }

    public function wantToPlay(Request $request)
    {
        $view_name = $this->request("want_to_play")::name();
        return view("guess-the-number.$view_name")->with($this->info);
    }

    public function guess(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|between:' . Globals::MIN_NUMBER . ',' . Globals::MAX_NUMBER
        ]);
        $number = $request->input('number');
        $view_name = $this->request("guess", $number)::name();
        return view("guess-the-number.$view_name")->with($this->info);
    }

    public function playAgain(Request $request)
    {
        $view_name = $this->request("play_again")::name();
        return view("guess-the-number.$view_name")->with($this->info);
    }
}
