<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;
use App\Traits\DebugHelper;

class StateManager
{
    use DebugHelper;
    protected array $arrStatesMap = [];
    protected array $eventQueue = [];
    protected array $clientRenderedAliases = [];
    protected array $refreshRequiredAliases = [];
    protected AbstractServiceManager $serviceManager;
    protected bool $isEnqueuedRefreshEvent = false;

    public final function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public final function enqueueEvent(array $eventInfo)
    {
        $rendered = $eventInfo['rendered'] ?? [];
        if (!empty($rendered)) {
            $this->clientRenderedAliases = $rendered;
        }
        $this->eventQueue[] = $eventInfo;
    }

    private function isRefreshRequired(string $strAlias): bool
    {
        $hasExplicitRefresh = in_array($strAlias, $this->refreshRequiredAliases);
        $notIsBeingRendered = !in_array($strAlias, $this->clientRenderedAliases);
        return $hasExplicitRefresh || $notIsBeingRendered;
    }

    public final function requireRefresh(string $strAlias)
    {
        $this->refreshRequiredAliases[] = $strAlias;
        $this->enqueueRefreshEvent();
    }

    private function enqueueRefreshEvent()
    {
        if ($this->isEnqueuedRefreshEvent) {
            return;
        }
        $this->enqueueEvent([
            'event' => 'refresh',
            'source' => null,
            'data' => [],
            'destination' => 'all'
        ]);
        $this->isEnqueuedRefreshEvent = true;
    }

    public final function getAllStatesViews()
    {
        $arrViews = [];
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            reset($this->arrStatesMap);
            while ($strAlias = key($this->arrStatesMap)) {
                if ($eventInfo['destination'] != 'all') {
                    $eventInfo['destination'] = $strAlias;
                }
                $stateContext = $this->arrStatesMap[$strAlias]['context'];
                $state = $stateContext->request($eventInfo);
                if (
                    $eventInfo['event'] == null ||
                    $stateContext->isStateChanged ||
                    $this->isRefreshRequired($strAlias)
                ) {
                    $view = $state->view($this->serviceManager->baseKebabName());
                    $view = base64_encode($view);
                    $arrViews[$strAlias] = $view;
                }
                next($this->arrStatesMap);
            }
            next($this->eventQueue);
        }
        return $arrViews;
    }

    public final function enqueueAllForRendering(
        array $arrModels,
        IStateManagedModel $parent = null
    ) {
        $enqueuedObjectAliases = [];
        if (count($arrModels) === 0) {
            return $enqueuedObjectAliases;
        }
        foreach ($arrModels as $object) {
            $enqueuedObjectAliases[] = $this->enqueueForRendering($object, $parent);
        }
        return $enqueuedObjectAliases;
    }

    public final function enqueueForRendering(
        IStateManagedModel $model,
        IStateManagedModel $parentModel = null
    ): string {
        $strModelAlias = $this->findOrCreateContext($model);
        if ($parentModel) {
            $strParentModelAlias = $this->findOrCreateContext($parentModel);
            $this->arrStatesMap[$strParentModelAlias]['children'][] = $strModelAlias;
        }
        return $strModelAlias;
    }

    private function findOrCreateContext(IStateManagedModel $model): string
    {
        $strAlias = $model->getAlias();
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
            $this->arrStatesMap[$strAlias]['children'] = [];
        }
        return $strAlias;
    }
}
