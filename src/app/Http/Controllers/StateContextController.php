<?php

namespace App\Http\Controllers;

use App\FSM\StateInterface;
use App\Services\AbstractServiceManager;
use Illuminate\Http\Request;
use App\FSM\StateContextInterface;
use App\FSM\StateStorageInterface;

abstract class StateContextController implements StateContextInterface
{
    private const INSTANCED_STATES_KEY = 'instanced_states';
    protected ?StateInterface $__state = null;
    protected StateStorageInterface $stateStorage;
    protected AbstractServiceManager $serviceManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

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
        if (property_exists($this, $attributeName)) {
            return $this->$attributeName;
        }
        return $this->serviceManager->$attributeName;
    }

    public function request(?string $event = null, $data = null): StateInterface
    {
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $current_state->handleRequest($event, $data);
            $changed_state = $this->__state;
            $this->stateStorage->saveState($changed_state::dashCaseName());
            $event = null;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $state_class = $this->stateStorage->readState();
        $state_dashed_name = $state_class::dashCaseName();
        $this->registerStateInstance($state_class);
        $this->stateStorage->saveState($state_dashed_name);
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        $stored_state_class = $instanced_states[$state_dashed_name]::class;
        $this->setState($stored_state_class);
    }

    public function reset(Request $request)
    {
        session()->forget(self::INSTANCED_STATES_KEY);
        $this->stateStorage->saveState(null);
        return redirect()->route("guess-the-number");
    }
}
