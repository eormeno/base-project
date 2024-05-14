<?php

namespace App\Http\Controllers;

use App\FSM\StateInterface;
use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

abstract class Controller implements StateContextInterface
{
    protected array $game_info;
    protected StateInterface $state;

    public function setState(StateInterface $state)
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
        $caller_namespace = substr(get_called_class(), 0, strrpos(get_called_class(), '\\') + 1);
        $this->setState(StateAbstractImpl::fromName($caller_namespace, session('game_info')['state']));
        return session('game_info');
    }
}
