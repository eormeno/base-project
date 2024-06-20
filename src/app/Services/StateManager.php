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

    public final function getAllStatesViews(
        array $eventInfo = Constants::EMPTY_EVENT,
        string $strControllerKebabName
    ) {
        $arrViews = [];
        reset($this->arrStatesMap);
        while ($key = key($this->arrStatesMap)) {
            $stateContext = $this->arrStatesMap[$key];
            $view = $stateContext->request($eventInfo)->view($strControllerKebabName);
            $view = base64_encode($view);
            $arrViews[$key] = $view;
            next($this->arrStatesMap);
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
        $strObjectContextInstanceKey = $alias ?? get_class($object) . $object->getId();
        if (!array_key_exists($strObjectContextInstanceKey, $this->arrStatesMap)) {
            $this->arrStatesMap[$strObjectContextInstanceKey] = new StateContextImpl($this, $serviceManager, $object);
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
