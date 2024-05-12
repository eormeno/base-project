<?php

namespace App\Http\Controllers;

use App\GTNEvent;
use App\GTNState;
use Illuminate\Http\Request;

class GuessTheNumberController extends Controller
{
    const MAX_ATTEMPTS = 5;
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 128;
    private array $game_info;

    public function index(Request $request)
    {
        $this->transition();
        return view('guess-the-number-2')->with($this->game_info);
    }

    public function wantToPlay(Request $request)
    {
        $this->transition(GTNEvent::want_to_play);
        return redirect()->route('guess-the-number')->with($this->game_info);
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
        $this->transition(GTNEvent::guess, $number);
        return redirect()->back()->with($this->game_info);
    }

    public function playAgain(Request $request)
    {
        $this->transition(GTNEvent::play_again);
        return redirect()->back()->with($this->game_info);
    }

    private function transition(GTNEvent $event = GTNEvent::none, $data = null)
    {
        do {
            $this->game_info = $this->getGameInfo();
            $state = $this->getGameState();
            $next_state = match ($state) {
                GTNState::INITIAL => $this->initial($event, $data),
                GTNState::PREPARING => $this->preparing($event, $data),
                GTNState::ASKING_PLAY => $this->askingPlay($event, $data),
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
        return GTNState::ASKING_PLAY;
    }

    private function askingPlay(GTNEvent $event, $data): GTNState
    {
        if ($event == GTNEvent::want_to_play) {
            return GTNState::PLAYING;
        }
        return GTNState::ASKING_PLAY;
    }

    private function playing(GTNEvent $event, $data): GTNState
    {
        if ($event == GTNEvent::guess) {
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
        if ($event == GTNEvent::play_again) {
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
