<?php

namespace App\Services;

use ReflectionClass;
use App\Utils\Constants;
use App\Utils\CaseConverters;
use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

class StateManager
{
    protected array $arrStatesMap = [];
    protected string $strControllerKebabName;
    protected array $eventQueue = [];

    public final function setControllerKebabName(string $strControllerKebabName)
    {
        $this->strControllerKebabName = $strControllerKebabName;
    }

    public final function enqueueEvent(array $eventInfo)
    {
        $this->eventQueue[] = $eventInfo;
    }

    public final function getAllStatesViews()
    {
        $arrViews = [];
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            reset($this->arrStatesMap);
            while ($key = key($this->arrStatesMap)) {
                $eventInfo['destination'] = $key;
                $stateContext = $this->arrStatesMap[$key];
                $view = $stateContext->request($eventInfo)->view($this->strControllerKebabName);
                $view = base64_encode($view);
                $arrViews[$key] = $view;
                next($this->arrStatesMap);
            }
            next($this->eventQueue);
        }
        return $arrViews;
    }

    public final function enqueueAllForRendering(
        AbstractServiceManager $serviceManager,
        array $arrObjects,
        bool $isUseObjectAlias = true
    ) {
        $enqueuedObjectAliases = [];
        if (count($arrObjects) === 0) {
            return $enqueuedObjectAliases;
        }
        $strAliasPrefix = '';
        if ($isUseObjectAlias) {
            $object = $arrObjects[0];
            $rflClass = new ReflectionClass($object);
            $strAliasPrefix = CaseConverters::toKebab($rflClass->getShortName());
        }
        foreach ($arrObjects as $object) {
            $alias = $isUseObjectAlias ? $strAliasPrefix . $object->getId() : null;
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
        $strAliasOrKey = $alias ?? get_class($object) . $object->getId();
        if (!array_key_exists($strAliasOrKey, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAliasOrKey] = new StateContextImpl($this, $serviceManager, $object);
        }
    }

    public final function reset()
    {
        reset($this->arrStatesMap);
        while ($key = key($this->arrStatesMap)) {
            $this->arrStatesMap[$key]->reset();
            next($this->arrStatesMap);
        }
    }
}
