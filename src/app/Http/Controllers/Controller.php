<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FSM\StateInterface;
use App\FSM\StateContextInterface;

abstract class Controller implements StateContextInterface
{
    protected array $info;
    protected StateInterface $__state;

    /**
     * A method that receives a class and instantiates it
     */
    public function setState($state_class): void
    {
        $new_instance = $this->getStateInstance($state_class);
        $new_instance->setContent($this);
        $this->__state = $new_instance;
    }

    private function getStateInstance($state_class): StateInterface
    {
        if (!in_array(StateInterface::class, class_implements($state_class))) {
            throw new \Exception("Class $state_class does not implement StateInterface");
        }
        $state_dashed_name = $state_class::name();
        if (!session()->has($state_dashed_name)) {
            session()->put($state_dashed_name, new $state_class());
        }
        return session($state_dashed_name);
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

    public function request(?string $event = null, $data = null): StateInterface
    {
        do {
            $this->info = $this->getGameInfo();
            $current_state = $this->__state;
            $this->__state->handleRequest($event, $data);
            $changed_state = $this->__state;
            $this->state = $changed_state::name();
            session()->put('info', $this->info);
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    private function getGameInfo(): array
    {
        if (!session()->has('info')) {
            $initial_state = $this->getInitialStateClass();
            session()->put('info', [
                'state' => $initial_state::name()
            ]);
            $this->setState($initial_state);
            return session('info');
        }
        $state_dashed_name = session('info')['state'];
        $stored_state_class = session($state_dashed_name)::class;
        $this->setState($stored_state_class);
        return session('info');
    }

    public function reset(Request $request)
    {
        session()->forget('info');
        $view_name = $this->request();
        return redirect()->route("guess-the-number");
    }
}
