<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\FSM\StateInterface;
use Illuminate\Http\Request;
use App\Utils\ReflectionUtils;
use App\Helpers\StatesLocalCache;
use App\FSM\StateContextInterface;
use App\FSM\StateStorageInterface;
use App\Services\AbstractServiceManager;

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
        $strThisControllerKebabName = ReflectionUtils::getKebabClassName($this, 'Controller');
        return view("$strThisControllerKebabName.index");
    }

    private function setState(ReflectionClass $reflection_state_class): void
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

    public function request(array $eventInfo): StateInterface
    {
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $this->setState($current_state->handleRequest($eventInfo));
            $changed_state = $this->__state;
            $this->stateStorage->saveState($changed_state::StateClass());
            $eventInfo['event'] = null;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $reflection_state_class = $this->stateStorage->readState();
        $sta_registered = StatesLocalCache::findRegisteredStateInstance($reflection_state_class);
        $this->stateStorage->saveState($reflection_state_class);
        $this->setState($sta_registered::StateClass());
    }

    public function reset()
    {
        StatesLocalCache::reset();
        $this->stateStorage->saveState(null);
        $str_this_controller_kebab_name = ReflectionUtils::getKebabClassName($this, 'Controller');
        return redirect()->route($str_this_controller_kebab_name);
    }

}
