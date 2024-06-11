<?php

namespace App\Http\Controllers\GuessTheNumber;

use Illuminate\Http\Request;
use App\Services\StateManager;
use App\Utils\ReflectionUtils;
use App\Helpers\StatesLocalCache;
use App\Http\Requests\EventRequestFilter;
use App\Http\Controllers\StateContextController;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends StateContextController
{
    private StateManager $stateManager;

    public function __construct(
        GuessTheNumberGameServiceManager $serviceManager
    ) {
        parent::__construct($serviceManager);
        $this->stateStorage = $serviceManager->gameStateStorageService;
        $this->stateManager = new StateManager($serviceManager);
    }

    public function index(Request $request)
    {
        $strThisControllerKebabName = ReflectionUtils::getKebabClassName($this, 'Controller');
        return view("$strThisControllerKebabName.index");
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->gameService->getGame();
        return $this->stateManager->getState($game, $request->eventInfo());

        //return $this->request($request->eventInfo())->view();
    }

    public function reset()
    {
        StatesLocalCache::reset();
        $this->stateStorage->saveState(null);
        $str_this_controller_kebab_name = ReflectionUtils::getKebabClassName($this, 'Controller');
        return redirect()->route($str_this_controller_kebab_name);
    }
}
