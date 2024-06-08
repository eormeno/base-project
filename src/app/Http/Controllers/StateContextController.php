<?php

namespace App\Http\Controllers;

use App\Utils\CaseConverters;
use ReflectionClass;
use App\FSM\StateInterface;
use Illuminate\Http\Request;
use App\FSM\StateContextInterface;
use App\FSM\StateStorageInterface;
use App\Services\AbstractServiceManager;

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

    public function setState(ReflectionClass $state_class): void
    {
        $new_instance = $this->getStateInstance($state_class);
        $new_instance->setContext($this);
        if ($new_instance->isNeedRestoring()) {
            $new_instance->setNeedRestoring(false);
            $new_instance->onEnter(true);
        }
        if ($this->__state && $this->__state != $new_instance) {
            $this->__state->onExit();
            $new_instance->onEnter(false);
        }
        $this->__state = $new_instance;
        $this->__state->onRefresh();
    }

    private function getStateInstance($state_class): StateInterface
    {
        $this->registerStateInstance($state_class);
        //$state_dashed_name = $state_class::dashCaseName();
        $state_dashed_name = CaseConverters::pascalToKebab($state_class->getShortName());
        return session(self::INSTANCED_STATES_KEY)[$state_dashed_name];
    }

    private function registerStateInstance(ReflectionClass $state_class): void
    {
        if (!in_array(StateInterface::class, $state_class->getInterfaceNames())) {
            throw new \Exception("The state class must implement the StateInterface.");
        }
        $need_restoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $need_restoring = true;
        }
        $state_dashed_name = CaseConverters::pascalToKebab($state_class->getShortName());
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($state_dashed_name, $instanced_states)) {
            $new_instance = $state_class->newInstance();
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
            $kebab_name = CaseConverters::pascalToKebab($changed_state::StateClass()->getShortName());
            $this->stateStorage->saveState($kebab_name);
            $event = null;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $state_class = $this->stateStorage->readState();
        $state_dashed_name = CaseConverters::pascalToKebab($state_class->getShortName());
        $this->registerStateInstance($state_class);
        $this->stateStorage->saveState($state_dashed_name);
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        $stored_state_class = $instanced_states[$state_dashed_name]::StateClass();
        $this->setState($stored_state_class);
    }

    public function reset(Request $request)
    {
        session()->forget(self::INSTANCED_STATES_KEY);
        $this->stateStorage->saveState(null);
        return redirect()->route("guess-the-number");
    }
}
