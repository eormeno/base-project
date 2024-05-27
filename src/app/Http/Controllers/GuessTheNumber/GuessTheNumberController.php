<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuessTheNumberController extends Controller
{
    public function getInitialStateClass()
    {
        return Initial::class;
    }

    public function index(Request $request)
    {
        $state = $this->request();
        $view_name = $state::dashCaseName();
        $view_attr = $state->toArray();
        return view("guess-the-number.$view_name", $view_attr);
    }

    public function wantToPlay(Request $request)
    {
        $state = $this->request("want_to_play");
        $view_name = $state::dashCaseName();
        $view_attr = $state->toArray();
        return view("guess-the-number.$view_name", $view_attr);
    }

    public function guess(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|between:' . Globals::MIN_NUMBER . ',' . Globals::MAX_NUMBER
        ]);
        $number = $request->input('number');
        $state = $this->request("guess", $number);
        $view_name = $state::dashCaseName();
        $view_attr = $state->toArray();
        return view("guess-the-number.$view_name", $view_attr);
    }

    public function playAgain(Request $request)
    {
        $state = $this->request("play_again");
        $view_name = $state::dashCaseName();
        $view_attr = $state->toArray();
        return view("guess-the-number.$view_name", $view_attr);
    }
}
