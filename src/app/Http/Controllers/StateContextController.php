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
        //$debug_msg = "";
        $new_instance = $this->getStateInstance($state_class);
        $new_instance->setContext($this);
        if ($new_instance->isNeedRestoring()) {
            $new_instance->setNeedRestoring(false);
            //$debug_msg = "Restoring: <b>" . $new_instance::dashCaseName() . "</b><br>";
            $new_instance->onEnter(true);
        }
        if ($this->__state && $this->__state != $new_instance) {
            //$debug_msg = "Exiting: <b>" . $this->__state::dashCaseName() . "</b><br>";
            $this->__state->onExit();
            //$debug_msg .= "Entering: <b>" . $new_instance::dashCaseName() . "</b><br>";
            $new_instance->onEnter(false);
        }
        //if ($debug_msg != "")
        //    echo "<hr>$debug_msg";
        $this->__state = $new_instance;
        $this->__state->onRefresh();
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
        $need_restoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $need_restoring = true;
        }
        $state_dashed_name = $state_class::dashCaseName();
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($state_dashed_name, $instanced_states)) {
            $new_instance = new $state_class();
            $new_instance->setNeedRestoring($need_restoring);
            $instanced_states[$state_dashed_name] = $new_instance;
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
