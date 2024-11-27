<?php

namespace App\Services;

use App\FSM\IStateModel;
use App\Models\AStateModel;
use App\Traits\DebugHelper;
use App\Helpers\StateModelReflect;
use App\Services\StateContextImpl;

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
        $stateContext = $this->findContext($strAlias);
        $state = $stateContext->request($eventInfo);
        $changed = $stateContext->isStateChanged;
        $refresh = $this->isRefreshRequired($strAlias);
        // $this->log("State $strAlias changed: $changed, refresh: $refresh");
        $this->addToRenderQueue($state->getChildren());
        // if ($changed) {

        //     // TODOs: Ver acá qué pasa
        //     $previousChildren = $stateContext->arrPreviousChildren;
        //     $this->log("Previous children: " . implode(', ', $previousChildren));
        //     // remove each child from previousChilddren from arrStatesMap
        //     foreach ($previousChildren as $childAlias) {
        //         //unset($this->arrStatesMap[$childAlias]);
        //     }
        // }
        if ($changed || $refresh) {
            $view = $state->view($this->serviceManager->baseKebabName());
            $view = base64_encode($view);
            $this->arrStatesMap[$strAlias]['view'] = $view;
        }
    }

    public final function statesViews(IStateModel $rootModel, array $eventInfo)
    {
        $currentTimestamp = microtime(true);
        $this->enqueueEvent($eventInfo);
        $this->arrStatesMap = $this->activeStates($rootModel);
        $this->addToRenderQueue($rootModel);
        reset($this->eventQueue);
        while ($eventInfo = current($this->eventQueue)) {
            $destination = $eventInfo['destination'];
            $this->logEvent($eventInfo, true);
            if ($destination != 'all') {
                $this->doRequest($destination, $eventInfo);
            } else {
                reset($this->arrStatesMap);
                while ($strAlias = key($this->arrStatesMap)) {
                    $this->doRequest($strAlias, $eventInfo);
                    next($this->arrStatesMap);
                }
            }
            next($this->eventQueue);
        }
        $views = $this->getViewsForRender($rootModel);
        $viewsCount = count($views) - 2; // root and actives are not views
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        $this->log("StateManager sent $viewsCount in $elapsed ms");
        return $views;
    }

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
        // ACÁ ENVIARÁ TODOS LOS ELEMENTOS ACTIVOS AL CLIENTE
        $arrViews['actives'] = $this->activeStates($rootModel, true);
        // $this->log("Actives: " . implode(', ', $arrViews['actives']));
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

    public final function reset()
    {
        // session()->forget(self::RENDERING_ALIASES);
    }

    private function activeStates(IStateModel $model, bool $onlyAlias = false): array
    {
        $alias = $model->getAlias();
        if (!$onlyAlias) {
            $activeStates[$alias]['model'] = $model;
            $activeStates[$alias]['view'] = null;
            $this->enqueueRefreshEvent($alias);
        } else {
            $activeStates[] = $alias;
        }
        $children = $this->getModelChildren($model);
        $choldrens = [];
        // TODO: el problema es que los hijos del modelo dependen del estado del modelo
        StateModelReflect::treeOfChildren($model, $choldrens);
        if (!empty($choldrens)) {
            $this->log("CHOLDREN of $alias: " . implode(', ', $choldrens));
        }
        if (!empty($children)) {
            //$this->log("Children of $alias: " . implode(', ', $children));
        }
        foreach ($children as $childAlias) {
            $childModel = AStateModel::modelOf($childAlias);
            $activeStates = array_merge($activeStates, $this->activeStates($childModel, $onlyAlias));
        }
        return $activeStates;
    }

    private function getModelChildren(IStateModel $model): array
    {
        $children = $model->children; // phpcs:ignore
        //if (!$children || empty($children) || !$model->_getState()) {  // que _getState no tenga valor no significa que no tenga hijos
        if (!$children || empty($children)) {
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
}
