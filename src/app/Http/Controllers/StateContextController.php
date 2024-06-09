<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\FSM\StateInterface;
use Illuminate\Http\Request;
use App\Utils\CaseConverters;
use App\Helpers\StatesLocalCache;
use App\FSM\StateContextInterface;
use App\FSM\StateStorageInterface;
use App\Services\AbstractServiceManager;
use App\Http\Requests\EventRequestFilter;

abstract class StateContextController implements StateContextInterface
{
    protected ?StateInterface $__state = null;
    protected StateStorageInterface $stateStorage;
    protected AbstractServiceManager $serviceManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function index(Request $request)
    {
        $debug = env('APP_DEBUG', false);
        $local_debug = $debug && false;
        $this_controller_kebab_name = $this->getKebabClassName();
        return view("$this_controller_kebab_name.index", ['debug' => $local_debug]);
    }

    public function event(EventRequestFilter $request)
    {
        $event = $request->eventInfo()['event'];
        $data = $request->eventInfo()['data'];
        return $this->request($event, $data)->view();
    }

    public function setState(ReflectionClass $reflection_state_class): void
    {
        $new_instance = StatesLocalCache::getStateInstance($reflection_state_class);
        $new_instance->setContext($this);
        if ($new_instance->isNeedRestoring()) {
            $new_instance->setNeedRestoring(false);
            $new_instance->onReload();
        }
        if ($this->__state && $this->__state != $new_instance) {
            $this->__state->onExit();
            $new_instance->onEnter();
        }
        $this->__state = $new_instance;
        $this->__state->onRefresh();
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
            $short_class_name = $changed_state::StateClass()->getShortName();
            $kebab_class_name = CaseConverters::pascalToKebab($short_class_name);
            $this->stateStorage->saveState($kebab_class_name);
            $event = null;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $reflection_state_class = $this->stateStorage->readState();
        $str_short_class_name = $reflection_state_class->getShortName();
        $str_state_kebab_name = CaseConverters::pascalToKebab($str_short_class_name);
        StatesLocalCache::registerStateInstance($reflection_state_class);
        $this->stateStorage->saveState($str_state_kebab_name);
        $stored_reflection_state_class = StatesLocalCache::getStateInstanceFromKey($str_state_kebab_name);
        $this->setState($stored_reflection_state_class);
    }

    public function reset(Request $request)
    {
        StatesLocalCache::reset();
        $this->stateStorage->saveState(null);
        $this_controller_kebab_name = $this->getKebabClassName();
        return redirect()->route($this_controller_kebab_name);
    }

    private function getKebabClassName(): string
    {
        $short_class_name = (new ReflectionClass($this))->getShortName();
        $short_class_name = substr($short_class_name, 0, -10);
        return CaseConverters::pascalToKebab($short_class_name);
    }
}
