<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FSM\StateInterface;
use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

abstract class Controller implements StateContextInterface
{
    protected array $info;
    protected StateInterface $__state;

    public function setState(StateInterface $state)
    {
        $this->__state = $state;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->info)) {
            return $this->info[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    public function request($event = null, $data = null)
    {
        do {
            $this->info = $this->getGameInfo();
            $current_state = $this->__state;
            $this->__state->handleRequest($this, $event, $data);
            $changed_state = $this->__state;
            $this->state = $changed_state->name();
            session()->put('info', $this->info);
        } while ($current_state != $changed_state);
    }

    private function getGameInfo(): array
    {
        if (!session()->has('info')) {
            session()->put('info', [
                'state' => 'initial',
                'message' => '',
            ]);
        }
        $caller_namespace = substr(get_called_class(), 0, strrpos(get_called_class(), '\\') + 1);
        $this->setState(StateAbstractImpl::fromName($caller_namespace, session('info')['state']));
        return session('info');
    }

    public function reset(Request $request)
    {
        session()->forget('info');
        $this->request();
        return redirect()->back()->with($this->info);
    }
}
