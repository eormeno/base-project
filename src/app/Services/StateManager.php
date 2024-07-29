<?php

namespace App\Services;

use App\FSM\IStateModel;
use App\Services\StateContextImpl;
use App\Traits\DebugHelper;

class StateManager
{
    use DebugHelper;

    private const RENDERING_ALIASES = 'rendering_aliases';

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
        $arrChildren = $this->findAllChildren($strAlias);
        foreach ($arrChildren as $childAlias) {
            $this->refreshRequiredAliases[] = $childAlias;
        }
        $this->enqueueRefreshForAliasEvent($strAlias);
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

    private function enqueueRefreshForAliasEvent(string $strAlias)
    {
        $this->enqueueEvent([
            'event' => 'refresh',
            'source' => null,
            'is_signal' => false,
            'data' => [],
            'destination' => $strAlias
        ]);
    }

    public final function getAllStatesViews2(IStateModel $rootModel)
    {
        //$this->resetRenderedAliases();
        $this->readRenderingAliases();
        $currentTimestamp = microtime(true);
        $this->enqueueForRendering($rootModel);
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            $destination = $eventInfo['destination'];
            $this->logEvent($eventInfo);
            reset($this->arrStatesMap);
            while ($strAlias = key($this->arrStatesMap)) {
                if ($eventInfo['destination'] != 'all') {
                    //$eventInfo['destination'] = $strAlias;
                }
                if ($destination && $destination != 'all' && $destination != $strAlias) {
                    // if($event == 'select') {
                    //     $this->log('Skipping ' . $strAlias);
                    // }
                    next($this->arrStatesMap);
                    continue;
                }
                $stateContext = $this->arrStatesMap[$strAlias]['context'];
                $state = $stateContext->request($eventInfo);
                $this->log(get_class($state) . ' ' . $stateContext->isStateChanged);
                $this->enqueueAllForRendering($state->getChildrenModels(), $state->getStateModel());
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
        $this->persistRenderingAliases();
        $this->eventQueue = [];
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
        return $strModelAlias;
    }

    private function findOrCreateContext(IStateModel $model): string
    {
        $strAlias = $model->getAlias();
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
            $this->arrStatesMap[$strAlias]['children'] = [];
            $this->arrStatesMap[$strAlias]['view'] = null;
            $this->log("Enqueued $strAlias");
            $this->enqueueRefreshForAliasEvent($strAlias);
        }
        return $strAlias;
    }

    public final function reset()
    {
        session()->forget(self::RENDERING_ALIASES);
    }

    private final function readRenderingAliases(): void
    {
        if (!session()->has(self::RENDERING_ALIASES)) {
            session()->put(self::RENDERING_ALIASES, []);
        }
        $this->arrStatesMap = session(self::RENDERING_ALIASES);
    }

    private final function persistRenderingAliases(): void
    {
        session()->put(self::RENDERING_ALIASES, $this->arrStatesMap);
    }

    private function logEvent(array $eventInfo)
    {
        unset($eventInfo['source']);
        unset($eventInfo['data']);
        unset($eventInfo['is_signal']);
        unset($eventInfo['rendered']);
        $this->log(json_encode($eventInfo));
    }
}
