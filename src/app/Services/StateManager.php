<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

class StateManager
{
    private const EMPTY_EVENT = ['event' => null, 'data' => null];
    // private const OBJECT_CONTEXT_KEY = 'objects_context';
    protected array $arrStatesMap = [];

    public final function getStatesViews(
        AbstractServiceManager $serviceManager,
        IStateManagedModel $object,
        array $eventInfo = ['event' => null, 'data' => null],
        string $strControllerKebabCaseName
    ) {
        $stateContext = $this->getStateContext($serviceManager, $object);
        return $stateContext->request($eventInfo)->view($strControllerKebabCaseName);
    }

    public final function getAllStatesViews(
        array $eventInfo = self::EMPTY_EVENT,
        string $strControllerKebabCaseName
    ) {
        $arrViews = [];
        foreach ($this->arrStatesMap as $stateContext) {
            $key = key($this->arrStatesMap);
            $view = $stateContext->request($eventInfo)->view($strControllerKebabCaseName);
            $view = base64_encode($view);
            $arrViews[$key] = $view;
        }
        return $arrViews;
    }

    public final function enqueueForRendering(
        AbstractServiceManager $serviceManager,
        IStateManagedModel $object,
        string|null $alias = null
    ) {
        $strObjectContextInstanceKey = "";
        if ($alias) {
            $strObjectContextInstanceKey = $alias;
        } else {
            $strObjectContextInstanceKey = get_class($object) . $object->getId();
        }
        if (!array_key_exists($strObjectContextInstanceKey, $this->arrStatesMap)) {
            $this->arrStatesMap[$strObjectContextInstanceKey] = new StateContextImpl($serviceManager, $object);
        }
    }

    // public final function registerModel(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    // {
    //     $strModelClassName = get_class($object);
    //     $intId = $object->getId();
    //     if (!session()->has(self::OBJECT_CONTEXT_KEY)) {
    //         session()->put(self::OBJECT_CONTEXT_KEY, []);
    //     }
    //     $strObjectContextInstanceKey = $strModelClassName . $intId;
    //     $arrObjectsContext = session(self::OBJECT_CONTEXT_KEY);
    //     if (!array_key_exists($strObjectContextInstanceKey, $arrObjectsContext)) {
    //         $stateContext = new StateContextImpl($serviceManager, $object);
    //         $arrObjectsContext[$strObjectContextInstanceKey] = $stateContext;
    //         session()->put(self::OBJECT_CONTEXT_KEY, $arrObjectsContext);
    //     }
    // }

    // public function getModelContextByClassNameAndId(string $strModelClassName, int $intId)
    // {
    //     $strObjectContextInstanceKey = $strModelClassName . $intId;
    //     $arrObjectsContext = session(self::OBJECT_CONTEXT_KEY);
    //     if (!array_key_exists($strObjectContextInstanceKey, $arrObjectsContext)) {
    //         throw new \Exception("The object context with key $strObjectContextInstanceKey does not exist.");
    //     }
    //     return $arrObjectsContext[$strObjectContextInstanceKey];
    // }

    private function getStateContext(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->arrStatesMap[$modelClassName])) {
            $this->arrStatesMap[$modelClassName] = ['state_context' => null];
        }
        $stateContext = $this->arrStatesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($serviceManager, $object);
            $this->arrStatesMap[$modelClassName]['state_context'] = $stateContext;
        }
        return $stateContext;
    }

    public final function reset(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    {
        $stateContext = $this->getStateContext($serviceManager, $object);
        $stateContext->reset();
    }
}
