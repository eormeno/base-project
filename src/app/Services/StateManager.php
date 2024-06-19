<?php

namespace App\Services;

use App\Utils\CaseConverters;
use ReflectionClass;
use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

class StateManager
{
    private const EMPTY_EVENT = ['event' => null, 'data' => null];
    protected array $arrStatesMap = [];

    public final function getAllStatesViews(
        array $eventInfo = self::EMPTY_EVENT,
        string $strControllerKebabCaseName
    ) {
        $arrViews = [];
        reset($this->arrStatesMap);
        $key = key($this->arrStatesMap);
        while($key) {
            $stateContext = $this->arrStatesMap[$key];
            $view = $stateContext->request($eventInfo)->view($strControllerKebabCaseName);
            $view = base64_encode($view);
            $arrViews[$key] = $view;
            next($this->arrStatesMap);
            $key = key($this->arrStatesMap);
        }
        return $arrViews;
    }

    public final function enqueueAllForRendering(
        AbstractServiceManager $serviceManager,
        array $arrObjects
    ) {
        $enqueuedObjectAliases = [];
        if (count($arrObjects) === 0) {
            return $enqueuedObjectAliases;
        }
        $object = $arrObjects[0];
        $rflClass = new ReflectionClass($object);
        $strAliasPrefix = CaseConverters::toKebab($rflClass->getShortName());
        foreach ($arrObjects as $object) {
            $alias = $strAliasPrefix . $object->getId();
            $this->enqueueForRendering($serviceManager, $object, $alias);
            $enqueuedObjectAliases[] = $alias;
        }
        return $enqueuedObjectAliases;
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
            $this->arrStatesMap[$strObjectContextInstanceKey] = new StateContextImpl($this, $serviceManager, $object);
        }
    }

    private function getStateContext(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->arrStatesMap[$modelClassName])) {
            $this->arrStatesMap[$modelClassName] = ['state_context' => null];
        }
        $stateContext = $this->arrStatesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($this, $serviceManager, $object);
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
