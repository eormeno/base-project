<?php

namespace App\Services;

use App\Models\GuessTheNumberGame;
use App\Services\StateContextImpl;
use App\States\GuessTheNumber\Initial;
use Illuminate\Database\Eloquent\Model;

class StateManager
{
    protected $statesMap = [
        GuessTheNumberGame::class => [
            'initial' => Initial::class,
            'state_field' => 'state',
            'id_field' => 'id',
            'state_context' => null
        ],
    ];
    protected $serviceManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getState(Model $object, array $eventInfo = ['event' => null, 'data' => null])
    {
        $stateContext = $this->getStateContext($object);
        return $stateContext->request($eventInfo)->view();
    }

    private function getStateContext(Model $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->statesMap[$modelClassName])) {
            throw new \Exception("State for $modelClassName is not defined.");
        }
        $stateConfig = $this->statesMap[$modelClassName];
        $stateContext = $this->statesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($this->serviceManager, $object, $stateConfig);
            $this->statesMap[$modelClassName]['state_context'] = $stateContext;
        }
        return $stateContext;
    }

    public function reset(Model $object)
    {
        $stateContext = $this->getStateContext($object);
        $stateContext->reset();
    }
}
