<?php

namespace App\Http\Controllers;

use App\MessageType;
use Illuminate\Http\Request;

class GuessTheNumberController extends Controller
{
    const MAX_ATTEMPTS = 5;

    private $messages = [
        'message' => '',
        'attempts' => self::MAX_ATTEMPTS,
        'mesage_type' => MessageType::INFO
    ];
    private int $randomNumber;

    public function index(Request $request)
    {
        $this->update();
        return view('guess-the-number')->with($this->messages);
    }

    public function guess(Request $request)
    {
        $number = $request->input('number');
        $this->update();
        $attempts = session()->decrement('attempts');
        $this->messages['attempts'] = $attempts;

        if ($attempts <= 0) {
            $this->reset();
            $this->messages['message'] = __('guess-the-number.game-over');
            $this->messages['mesage_type'] = MessageType::ERROR;
            return redirect()->back()->with($this->messages);
        }

        if ($number < $this->randomNumber) {
            $this->messages['message'] = __('guess-the-number.greater');
        }

        if ($number > $this->randomNumber) {
            $this->messages['message'] = __('guess-the-number.lower');
        }

        if ($number == $this->randomNumber) {
            $this->reset();
            $this->messages['message'] = __('guess-the-number.success');
            $this->messages['mesage_type'] = MessageType::SUCCESS;
        }

        return redirect()->back()->with($this->messages);
    }

    private function update()
    {
        if (!session()->has('randomNumber')) {
            $this->reset();
        }
        $this->messages['attempts'] = session('attempts');
        $this->randomNumber = session('randomNumber');
    }

    private function reset()
    {
        session()->put('randomNumber', rand(1, 100));
        session()->put('attempts', self::MAX_ATTEMPTS);
    }
}
