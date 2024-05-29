<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Http\Requests\EventRequestFilter;
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
        //return $this->request()->view();
        return view('guess-the-number.index');
    }

    public function event(EventRequestFilter $request)
    {
        $event = $request->eventInfo()['event'];
        $data = $request->eventInfo()['data'];
        return $this->request($event, $data)->view();
    }

}
