<?php

namespace App\Services;

use App\FSM\IStateModel;
use App\Models\AStateModel;
use App\Traits\DebugHelper;
use App\Services\StateContextImpl;
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
        // $arrChildren = $this->findAllChildren($strAlias);
        // foreach ($arrChildren as $childAlias) {
        //     $this->refreshRequiredAliases[] = $childAlias;
        // }
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
        $currentTimestamp = microtime(true);
        $this->readRenderingAliases();
        $this->register4Render($rootModel);
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            $destination = $eventInfo['destination'];
            $this->logEvent($eventInfo);
            reset($this->arrStatesMap);
            while ($strAlias = key($this->arrStatesMap)) {
                if ($destination && $destination != 'all' && $destination != $strAlias) {
                    next($this->arrStatesMap);
                    continue;
                }
                $stateContext = $this->findContext($strAlias);
                $state = $stateContext->request($eventInfo);
                //$this->enqueueAllForRendering($state->getChildren(), $state->getStateModel());
                $this->register4Render($state->getChildren());
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
        $views = $this->getViewsForRender($rootModel);
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        $this->log("StateManager {$elapsed}ms");
        $this->persistRenderingAliases();
        $this->eventQueue = [];
        return $views;
    }

    private function getViewsForRender(IStateModel $rootModel): array
    {
        $arrViews = [];
        $arrViews['root'] = $rootModel->getAlias();
        foreach ($this->arrStatesMap as $strAlias => $arrState) {
            $view = $arrState['view'];
            if ($view) {
                $arrViews[$strAlias] = $view;
            }
        }
        // $tree = $this->getTree();
        // foreach ($tree as $strAlias) {
        //     $view = $this->arrStatesMap[$strAlias]['view'];
        //     if ($view) {
        //         $arrViews[$strAlias] = $view;
        //     }
        // }
        // $arrViews['tree'] = $tree;
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

    public final function register4Render(IStateModel|array|string $models): void
    {
        if (is_array($models)) {
            foreach ($models as $model) {
                $this->register4Render($model);
            }
            return;
        }
        if (is_string($models)) {
            $models = AStateModel::modelOf($models);
        }
        $alias = $models->getAlias();
        if (!array_key_exists($alias, $this->arrStatesMap)) {
            $this->arrStatesMap[$alias]['view'] = null;
            $this->enqueueRefreshForAliasEvent($alias);
        }
        $this->arrStatesMap[$alias]['model'] = $models;
        $this->arrStatesMap[$alias]['context'] = new StateContextImpl($this->serviceManager, $models);
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

    private function findContext(string $strAlias): StateContextImpl
    {
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            throw new \Exception("Alias $strAlias not found");
        }
        if (!array_key_exists('model', $this->arrStatesMap[$strAlias])) {
            $this->arrStatesMap[$strAlias]['model'] = AStateModel::modelOf($strAlias);
        }
        if (!array_key_exists('context', $this->arrStatesMap[$strAlias])) {
            $model = $this->arrStatesMap[$strAlias]['model'];
            $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
        }
        return $this->arrStatesMap[$strAlias]['context'];
    }

    private function findOrCreateContext(IStateModel $model): string
    {
        $strAlias = $model->getAlias();
        if (!array_key_exists($strAlias, $this->arrStatesMap)) {
            $this->arrStatesMap[$strAlias]['children'] = [];
            $this->arrStatesMap[$strAlias]['view'] = null;
            $this->enqueueRefreshForAliasEvent($strAlias);
        }
        $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
        $this->arrStatesMap[$strAlias]['model'] = $model;
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
        foreach ($this->arrStatesMap as $strAlias => $arrState) {
            unset($this->arrStatesMap[$strAlias]['context']);
            unset($this->arrStatesMap[$strAlias]['model']);
        }
        session()->put(self::RENDERING_ALIASES, $this->arrStatesMap);
    }
}
