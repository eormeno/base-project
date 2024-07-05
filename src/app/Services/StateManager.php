<?php

namespace App\Services;

use App\FSM\IStateModel;
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
    protected IStateModel $rootModel;

    public final function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        // TODO: Stdy this instantiation
        // $this->log('StateManager Constructed');
    }

    public final function setRootModel(IStateModel $model)
    {
        $this->rootModel = $model;
    }

    private function resetRenderedAliases()
    {
        $this->arrStatesMap = [];
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
        $arrChildren = $this->findAllChildren($strAlias);
        foreach ($arrChildren as $childAlias) {
            $this->refreshRequiredAliases[] = $childAlias;
        }
        $this->enqueueRefreshEvent();
    }

    private function getTree(): array
    {
        reset($this->arrStatesMap);
        return $this->findAllChildren(key($this->arrStatesMap));
    }

    private function findAllChildren(string $strAlias): array
    {
        $arrChildren = [];
        $arrChildren[] = $strAlias;
        foreach ($this->arrStatesMap[$strAlias]['children'] as $childAlias) {
            $arrChildren = array_merge($arrChildren, $this->findAllChildren($childAlias));
        }
        return $arrChildren;
    }

    private function enqueueRefreshEvent()
    {
        if ($this->isEnqueuedRefreshEvent) {
            return;
        }
        $this->enqueueEvent([
            'event' => 'refresh',
            'source' => null,
            'is_signal' => true,
            'data' => [],
            'destination' => 'all'
        ]);
        $this->isEnqueuedRefreshEvent = true;
    }

    public final function getAllStatesViews2()
    {
        $this->resetRenderedAliases();
        $currentTimestamp = microtime(true);
        $this->enqueueForRendering($this->rootModel);
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            reset($this->arrStatesMap);
            while ($strAlias = key($this->arrStatesMap)) {
                if ($eventInfo['destination'] != 'all') {
                    $eventInfo['destination'] = $strAlias;
                }
                $stateContext = $this->arrStatesMap[$strAlias]['context'];
                $state = $stateContext->request($eventInfo);
                $this->enqueueAllForRendering($state->getChildrenModels(), $state->model);
                if (
                    $eventInfo['event'] == null ||
                    $stateContext->isStateChanged ||
                    $this->isRefreshRequired($strAlias)
                ) {
                    $view = $state->view($this->serviceManager->baseKebabName());
                    $view = base64_encode($view);
                    $this->arrStatesMap[$strAlias]['view'] = $view;
                }
                next($this->arrStatesMap);
            }
            next($this->eventQueue);
        }
        $views = $this->getViewsForRender();
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        //$this->log('StateManager ' . $elapsed . 'ms');
        return $views;
    }

    public final function getAllStatesViews()
    {
        $currentTimestamp = microtime(true);
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
                    $this->arrStatesMap[$strAlias]['view'] = $view;
                }
                next($this->arrStatesMap);
            }
            next($this->eventQueue);
        }
        $views = $this->getViewsForRender();
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        //$this->log('StateManager ' . $elapsed . 'ms');
        return $views;
    }

    private function getViewsForRender(): array
    {
        $arrViews = [];
        $tree = $this->getTree();
        foreach ($tree as $strAlias) {
            $view = $this->arrStatesMap[$strAlias]['view'];
            if ($view) {
                $arrViews[$strAlias] = $view;
            }
        }
        //$arrViews['tree'] = $tree;
        return $arrViews;
    }

    public final function enqueueAllForRendering(
        array $arrModels,
        IStateModel $parent = null
    ): array {
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
        IStateModel $model,
        IStateModel $parentModel = null
    ): string {
        $strModelAlias = $this->findOrCreateContext($model);
        if ($parentModel) {
            $strParentModelAlias = $this->findOrCreateContext($parentModel);
            if (!in_array($strModelAlias, $this->arrStatesMap[$strParentModelAlias]['children'])) {
                $this->arrStatesMap[$strParentModelAlias]['children'][] = $strModelAlias;
            }
        }
        //$this->log('Enqueued ' . $strModelAlias);
        return $strModelAlias;
    }

    private function findOrCreateContext(IStateModel $model): string
    {
        $strAlias = $model->getAlias();
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
            $this->arrStatesMap[$strAlias]['children'] = [];
            $this->arrStatesMap[$strAlias]['view'] = null;
        }
        return $strAlias;
    }
}
