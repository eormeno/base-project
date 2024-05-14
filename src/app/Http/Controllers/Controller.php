<?php

namespace App\Http\Controllers;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNStates\GTNState;

abstract class Controller implements FSMContext
{
    protected array $game_info;
    protected GTNState $state;

    public function setState(GTNState $state)
    {
        $this->state = $state;
    }

    public function setValue(string $key, $value)
    {
        $this->game_info[$key] = $value;
    }

    public function getValue(string $key)
    {
        return $this->game_info[$key];
    }

    public function request($event = null, $data = null)
    {
        do {
            $this->game_info = $this->getGameInfo();
            $current_state = $this->state;
            $this->state->handleRequest($this, $event, $data);
            $changed_state = $this->state;
            $this->setValue('state', $changed_state->name());
            session()->put('game_info', $this->game_info);
        } while ($current_state != $changed_state);
    }

    private function getGameInfo(): array
    {
        if (!session()->has('game_info')) {
            session()->put('game_info', [
                'state' => 'initial',
                'message' => '',
            ]);
        }
        $this->setState(GTNState::fromName(session('game_info')['state']));
        return session('game_info');
    }
}
