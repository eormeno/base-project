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
        return $this->request()->view();
    }

    public function wantToPlay(Request $request)
    {
        return $this->request("want_to_play")->view();
    }

    public function guess(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|between:' . Globals::MIN_NUMBER . ',' . Globals::MAX_NUMBER
        ]);
        $number = $request->input('number');
        return $this->request("guess", $number)->view();
    }

    public function playAgain(Request $request)
    {
        return $this->request("play_again")->view();
    }
}
