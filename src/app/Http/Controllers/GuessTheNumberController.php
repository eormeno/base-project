<?php

namespace App\Http\Controllers;

use App\GTNEvent;
use App\GTNState;
use App\MessageType;
use Illuminate\Http\Request;

class GuessTheNumberController extends Controller
{
    const MAX_ATTEMPTS = 5;
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 128;

    private $messages = [
        'message' => '',
        'attempts' => self::MAX_ATTEMPTS,
        'mesage_type' => MessageType::INFO
    ];
    private int $randomNumber;
    private array $game_info;

    public function index(Request $request)
    {
        //$this->update();
        $this->transition();
        return view('guess-the-number-2')->with($this->game_info);
    }

    public function reset(Request $request)
    {
        session()->forget('game_info');
        $this->transition();
        return redirect()->route('guess-the-number')->with($this->game_info);
    }

    public function guess(Request $request)
    {
        $number = $request->input('number');
        $this->transition(GTNEvent::GUESS, $number);
        return redirect()->back()->with($this->game_info);
    }

    public function playAgain(Request $request)
    {
        $this->transition(GTNEvent::PLAY_AGAIN);
        return redirect()->back()->with($this->game_info);
    }

    public function __guess(Request $request)
    {
        $number = $request->input('number');
        $this->update();
        $attempts = session()->decrement('attempts');
        $this->messages['attempts'] = $attempts;

        if ($attempts <= 0) {
            //$this->reset();
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
            //$this->reset();
            $this->messages['message'] = __('guess-the-number.success');
            $this->messages['mesage_type'] = MessageType::SUCCESS;
        }

        return redirect()->back()->with($this->messages);
    }

    private function update()
    {
        if (!session()->has('randomNumber')) {
            //$this->reset();
        }
        $this->messages['attempts'] = session('attempts');
        $this->randomNumber = session('randomNumber');
    }

    private function __reset()
    {
        session()->put('randomNumber', rand(1, 100));
        session()->put('attempts', self::MAX_ATTEMPTS);
    }

    private function transition(GTNEvent $event = GTNEvent::NONE, $data = null)
    {
        do {
            $this->game_info = $this->getGameInfo();
            $state = $this->getGameState();
            $next_state = match ($state) {
                GTNState::INITIAL => $this->initial($event, $data),
                GTNState::PREPARING => $this->preparing($event, $data),
                GTNState::PLAYING => $this->playing($event, $data),
                GTNState::GAME_OVER => $this->gameOver($event, $data),
                GTNState::SUCCESS => $this->success($event, $data),
                GTNState::ASKING_PLAY_AGAIN => $this->askingPlayAgain($event, $data),
            };
            $this->game_info['state'] = $next_state->value;
            session()->put('game_info', $this->game_info);
        } while ($state != $next_state);
    }

    private function initial(GTNEvent $event, $data): GTNState
    {
        $this->game_info['min_number'] = self::MIN_NUMBER;
        $this->game_info['max_number'] = self::MAX_NUMBER;
        return GTNState::PREPARING;
    }

    private function preparing(GTNEvent $event, $data): GTNState
    {
        $this->game_info['random_number'] = rand(self::MIN_NUMBER, self::MAX_NUMBER);
        $this->game_info['remaining_attempts'] = self::MAX_ATTEMPTS;
        $this->game_info['message'] = '';
        return GTNState::PLAYING;
    }

    private function playing(GTNEvent $event, $data): GTNState
    {
        if ($event == GTNEvent::GUESS) {
            $this->game_info['remaining_attempts']--;
            if ($data < $this->game_info['random_number']) {
                $this->game_info['message'] = __('guess-the-number.greater');
            } elseif ($data > $this->game_info['random_number']) {
                $this->game_info['message'] = __('guess-the-number.lower');
            } else {
                return GTNState::SUCCESS;
            }
            if ($this->game_info['remaining_attempts'] == 0) {
                return GTNState::GAME_OVER;
            }
        }
        return GTNState::PLAYING;
    }

    private function gameOver(GTNEvent $event, $data): GTNState
    {
        $this->game_info['message'] = __('guess-the-number.game-over');
        return GTNState::ASKING_PLAY_AGAIN;
    }

    private function success(GTNEvent $event, $data): GTNState
    {
        $this->game_info['message'] = __('guess-the-number.success');
        return GTNState::ASKING_PLAY_AGAIN;
    }

    private function askingPlayAgain(GTNEvent $event, $data): GTNState
    {
        if ($event == GTNEvent::PLAY_AGAIN) {
            return GTNState::PREPARING;
        }
        return GTNState::ASKING_PLAY_AGAIN;
    }

    private function getGameInfo(): array
    {
        if (!session()->has('game_info')) {
            session()->put('game_info', [
                'state' => GTNState::INITIAL->value,
                'message' => '',
            ]);
        }
        return session('game_info');
    }

    private function getGameState(): GTNState
    {
        return GTNState::from($this->getGameInfo()['state']);
    }
}
