<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

class StateManager
{
    protected array $arrStatesMap = [];
    protected array $eventQueue = [];

    public final function __construct(protected AbstractServiceManager $serviceManager)
    {
    }

    public final function enqueueEvent(array $eventInfo)
    {
        $this->eventQueue[] = $eventInfo;
    }

    public final function getAllStatesViews(string $strControllerKebabName)
    {
        $arrViews = [];
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            reset($this->arrStatesMap);
            while ($strAlias = key($this->arrStatesMap)) {
                if ($eventInfo['destination'] != 'all') {
                    $eventInfo['destination'] = $strAlias;
                }
                $stateContext = $this->arrStatesMap[$strAlias];
                $view = $stateContext->request($eventInfo)->view($strControllerKebabName);
                $view = base64_encode($view);
                $arrViews[$strAlias] = $view;
                next($this->arrStatesMap);
            }
            next($this->eventQueue);
        }
        return $arrViews;
    }

    public final function enqueueAllForRendering(
        array $arrObjects,
        bool $isUseShortAlias = false
    ) {
        $enqueuedObjectAliases = [];
        if (count($arrObjects) === 0) {
            return $enqueuedObjectAliases;
        }
        foreach ($arrObjects as $object) {
            $this->enqueueForRendering($object);
            $enqueuedObjectAliases[] = $object->getAlias();
        }
        return $enqueuedObjectAliases;
    }

    public final function enqueueForRendering(IStateManagedModel $object)
    {
        $strAlias = $object->getAlias();
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAlias] = new StateContextImpl($this->serviceManager, $object);
        }
    }
}
