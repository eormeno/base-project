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
        // todo: mejorar esto urgente! un reload no debería ser encolado. Pero no me gusta
        // que el StateManager tenga que saber qué eventos no encolar.
        if ($eventInfo['event'] != 'reload') {
            $this->eventQueue[] = $eventInfo;
        }
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
        // $arrChildren = $this->findAllChildren($strAlias);
        // foreach ($arrChildren as $childAlias) {
        //     $this->refreshRequiredAliases[] = $childAlias;
        // }
        //$this->enqueueRefreshForAliasEvent($strAlias);
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

    private function enqueueRefreshEvent(string $strAlias)
    {
        if (!$this->isRefreshRequired($strAlias)) {
            return;
        }
        $this->enqueueEvent([
            'event' => 'refresh',
            'source' => null,
            'is_signal' => false,
            'data' => [],
            'destination' => $strAlias
        ]);
    }

    public final function addToRenderQueue(IStateModel|array|string $models): void
    {
        if (is_array($models)) {
            foreach ($models as $model) {
                $this->addToRenderQueue($model); // recursive
            }
            return;
        }
        if (is_string($models)) {
            $models = AStateModel::modelOf($models);
        }
        $alias = $models->getAlias();
        if (!array_key_exists($alias, $this->arrStatesMap)) {
            $this->arrStatesMap[$alias]['view'] = null;
            // acá tampoco deberíamos encolar un refresh si el cliente ya lo renderizó
            $this->enqueueRefreshEvent($alias);
        }
        $this->arrStatesMap[$alias]['model'] = $models;
        $this->arrStatesMap[$alias]['context'] = new StateContextImpl($this->serviceManager, $models);
    }

    private function doRequest(string $strAlias, array $eventInfo): void
    {
        // $destination = $eventInfo['destination'];
        // if ($destination && $destination != 'all' && $destination != $strAlias) {
        //     //next($this->arrStatesMap);
        //     return;
        // }
        $this->logEvent($eventInfo, false);
        $stateContext = $this->findContext($strAlias);
        $state = $stateContext->request($eventInfo);
        $changed = $stateContext->isStateChanged;
        $refresh = $this->isRefreshRequired($strAlias);
        // $this->log("State $strAlias changed: $changed, refresh: $refresh");
        $this->addToRenderQueue($state->getChildren());
        if ($changed || $refresh) {
            $view = $state->view($this->serviceManager->baseKebabName());
            $view = base64_encode($view);
            $this->arrStatesMap[$strAlias]['view'] = $view;
        }
    }

    public final function statesViews(IStateModel $rootModel, array $eventInfo)
    {
        $currentTimestamp = microtime(true);
        $this->arrStatesMap = $this->restoreCachedRenderings($rootModel);
        $this->log("StateManager read " . count($this->arrStatesMap) . " cached renderings");
        $this->enqueueEvent($eventInfo);
        $this->addToRenderQueue($rootModel);
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            $destination = $eventInfo['destination'];
            if ($destination != 'all') {
                $this->doRequest($destination, $eventInfo);
            } else {
                reset($this->arrStatesMap);
                while ($strAlias = key($this->arrStatesMap)) {
                    $this->doRequest($strAlias, $eventInfo);
                    // if ($destination && $destination != 'all' && $destination != $strAlias) {
                    //     next($this->arrStatesMap);
                    //     continue;
                    // }
                    // $stateContext = $this->findContext($strAlias);
                    // $state = $stateContext->request($eventInfo);
                    // $this->addToRenderQueue($state->getChildren());
                    // if (
                    //     $stateContext->isStateChanged ||
                    //     $this->isRefreshRequired($strAlias)
                    // ) {
                    //     $view = $state->view($this->serviceManager->baseKebabName());
                    //     $view = base64_encode($view);
                    //     $this->arrStatesMap[$strAlias]['view'] = $view;
                    // }
                    next($this->arrStatesMap);
                }
            }
            next($this->eventQueue);
        }
        $views = $this->getViewsForRender($rootModel);
        $viewsCount = count($views) - 1; // root is not a view
        $this->persistRenderingAliases();
        $this->eventQueue = [];
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        $this->log("StateManager sent $viewsCount in $elapsed ms");
        return $views;
    }

    // private function readRenderingAliases(IStateModel $rootModel, array $eventInfo): void
    // {
    //     $event = $eventInfo['event'];
    //     if ($event != 'reload') {
    //         $this->enqueueEvent($eventInfo);
    //         return;
    //     }

    //     $clientRenderings = $eventInfo['rendered'] ?? [];
    //     $serverRenderings = $this->restoreCachedRenderings($rootModel);
    //     $clientRenderingCount = count($clientRenderings);
    //     $serverRenderingCount = count($serverRenderings);

    //     if ($serverRenderingCount == 0) {
    //         $this->log("No server renderings found");
    //         $this->addToRenderQueue($rootModel);
    //         return;
    //     }

    //     if ($clientRenderingCount > 0 && $serverRenderingCount == $clientRenderingCount) {
    //         // iterate all the serverRenderings, and if its view is null, we enqueue a refresh event
    //         foreach ($serverRenderings as $strAlias => $arrState) {
    //             if ($arrState['view'] == null) {
    //                 $this->enqueueRefreshForAliasEvent($strAlias);
    //             }
    //         }
    //         $this->arrStatesMap = $serverRenderings;
    //     }

    //     if (empty($serverRenderings)) {
    //         $serverRenderings = $this->activeStates($rootModel);
    //     }
    // }


    private function getViewsForRender(IStateModel $rootModel): array
    {
        $arrViews = [];
        $arrViews['root'] = $rootModel->getAlias();
        foreach ($this->arrStatesMap as $strAlias => $arrState) {
            // if view key is not set or is null, we don't render it
            if (!array_key_exists('view', $arrState) || $arrState['view'] == null) {
                continue;
            }
            $arrViews[$strAlias] = $arrState['view'];
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

    // public final function enqueueAllForRendering(
    //     array $arrModels,
    //     IStateModel $parent = null
    // ): array {
    //     $enqueuedObjectAliases = [];
    //     if (count($arrModels) === 0) {
    //         return $enqueuedObjectAliases;
    //     }
    //     foreach ($arrModels as $object) {
    //         $enqueuedObjectAliases[] = $this->enqueueForRendering($object, $parent);
    //     }
    //     return $enqueuedObjectAliases;
    // }

    // public final function enqueueForRendering(
    //     IStateModel $model,
    //     IStateModel $parentModel = null
    // ): string {
    //     $strModelAlias = $this->findOrCreateContext($model);
    //     if ($parentModel) {
    //         $strParentModelAlias = $this->findOrCreateContext($parentModel);
    //         if (!in_array($strModelAlias, $this->arrStatesMap[$strParentModelAlias]['children'])) {
    //             $this->arrStatesMap[$strParentModelAlias]['children'][] = $strModelAlias;
    //         }
    //     }
    //     return $strModelAlias;
    // }

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

    // private function findOrCreateContext(IStateModel $model): string
    // {
    //     $strAlias = $model->getAlias();
    //     if (!array_key_exists($strAlias, $this->arrStatesMap)) {
    //         $this->arrStatesMap[$strAlias]['children'] = [];
    //         $this->arrStatesMap[$strAlias]['view'] = null;
    //         $this->enqueueRefreshForAliasEvent($strAlias);
    //     }
    //     $this->arrStatesMap[$strAlias]['context'] = new StateContextImpl($this->serviceManager, $model);
    //     $this->arrStatesMap[$strAlias]['model'] = $model;
    //     return $strAlias;
    // }

    public final function reset()
    {
        session()->forget(self::RENDERING_ALIASES);
    }

    private function activeStates(IStateModel $model): array
    {
        $alias = $model->getAlias();
        //$activeStates[$alias]['context'] = new StateContextImpl($this->serviceManager, $model);
        $activeStates[$alias]['model'] = $model;
        $activeStates[$alias]['view'] = null;
        // Esto se debería hacer si el cliente no lo renderizó
        $this->enqueueRefreshEvent($alias);
        $children = $this->getModelChildren($model);
        foreach ($children as $childAlias) {
            $childModel = AStateModel::modelOf($childAlias);
            $activeStates = array_merge($activeStates, $this->activeStates($childModel));
        }
        return $activeStates;
    }

    private function getModelChildren(IStateModel $model): array
    {
        $children = $model->children; // phpcs:ignore
        if (!$children || empty($children) || !$model->getState()) {
            return [];
        }
        $ret = [];
        foreach ($children as $viewId => $strAlias) {
            if (is_array($strAlias)) {
                foreach ($strAlias as $alias) {
                    $ret[] = $alias;
                }
            } else {
                $ret[] = $strAlias;
            }
        }
        return $ret;
    }

    private function restoreCachedRenderings(IStateModel $rootModel): array
    {
        $cachedRenderings = [];
        if (!session()->has(self::RENDERING_ALIASES)) {
            session()->put(self::RENDERING_ALIASES, []);
        } else {
            $cachedRenderings = session(self::RENDERING_ALIASES);
        }
        if (empty($cachedRenderings)) {
            // Puede ocurrir que se haya cerrado la sesión y se haya perdido la lista de elementos
            // renderizados. En ese caso deberíamos reconstruirla a partir de la raíz del modelo.
            $cachedRenderings = $this->activeStates($rootModel);
            $this->log("No cached renderings found. Rebuilding from root model");
        }
        return $cachedRenderings;
    }

    private function persistRenderingAliases(): void
    {
        if (empty($this->arrStatesMap)) {
            return;
        }
        foreach ($this->arrStatesMap as $strAlias => $arrState) {
            unset($this->arrStatesMap[$strAlias]['context']);
            unset($this->arrStatesMap[$strAlias]['model']);
            unset($this->arrStatesMap[$strAlias]['view']);
        }
        session()->put(self::RENDERING_ALIASES, $this->arrStatesMap);
    }
}
