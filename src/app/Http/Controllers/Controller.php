<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FSM\StateInterface;
use App\FSM\StateContextInterface;

abstract class Controller implements StateContextInterface
{
    private const INFO_KEY = 'info';
    private const INSTANCED_STATES_KEY = 'instanced_states';
    protected array $info;
    protected ?StateInterface $__state = null;

    public function setState($state_class): void
    {
        $new_instance = $this->getStateInstance($state_class);
        $new_instance->setContext($this);
        if ($this->__state && $this->__state != $new_instance) {
            $this->__state->onExit();
            $new_instance->onEnter();
        }
        $this->__state = $new_instance;
    }

    private function getStateInstance($state_class): StateInterface
    {
        $this->registerStateInstance($state_class);
        $state_dashed_name = $state_class::dashCaseName();
        return session(self::INSTANCED_STATES_KEY)[$state_dashed_name];
    }

    private function registerStateInstance($state_class): void
    {
        if (!in_array(StateInterface::class, class_implements($state_class))) {
            throw new \Exception("Class $state_class does not implement StateInterface");
        }
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
        }
        $state_dashed_name = $state_class::dashCaseName();
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($state_dashed_name, $instanced_states)) {
            $instanced_states[$state_dashed_name] = new $state_class();
            session()->put(self::INSTANCED_STATES_KEY, $instanced_states);
        }
    }

    public function __get($attributeName)
    {
        if (array_key_exists($attributeName, $this->info)) {
            return $this->info[$attributeName];
        }
        return null;
    }

    public function __set($attributeName, $value)
    {
        $this->info[$attributeName] = $value;
    }

    public function request(?string $event = null, $data = null): StateInterface
    {
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $current_state->handleRequest($event, $data);
            $changed_state = $this->__state;
            $this->state = $changed_state::dashCaseName();
            session()->put(self::INFO_KEY, $this->info);
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    private function restoreState(): void
    {
        if (!session()->has(self::INFO_KEY)) {
            $initial_state = $this->getInitialStateClass();
            session()->put(self::INFO_KEY, [
                'state' => $initial_state::dashCaseName()
            ]);
            $this->registerStateInstance($initial_state);
        }
        $state_dashed_name = session(self::INFO_KEY)['state'];
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        $stored_state_class = $instanced_states[$state_dashed_name]::class;
        $this->setState($stored_state_class);
        $this->info = session(self::INFO_KEY);
    }

    public function reset(Request $request)
    {
        session()->forget(self::INFO_KEY);
        session()->forget(self::INSTANCED_STATES_KEY);
        return redirect()->route("guess-the-number");
    }
}
