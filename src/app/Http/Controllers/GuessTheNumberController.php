<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuessTheNumberController extends Controller
{

    public function __invoke(Request $request)
    {
        if (!session()->has('randomNumber')) {
            session(['randomNumber' => rand(1, 100)]);
        }
        return view('guess-the-number');
    }

    public function guess(Request $request)
    {
        $number = $request->input('number');
        $randomNumber = session('randomNumber');

        if ($number < $randomNumber) {
            return redirect()->back()->with('message', __('guess-the-number.lower'));
        }

        if ($number > $randomNumber) {
            return redirect()->back()->with('message', __('guess-the-number.greater'));
        }

        session()->forget('randomNumber');

        return redirect()->back()->with('message', __('guess-the-number.success'));
    }
}
