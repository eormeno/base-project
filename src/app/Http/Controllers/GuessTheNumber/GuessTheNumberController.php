<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StateManager;
use App\Utils\ReflectionUtils;
use App\Helpers\StatesLocalCache;
use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends Controller
{
    private StateManager $stateManager;

    public function __construct(
        protected GuessTheNumberGameServiceManager $serviceManager
    ) {
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
    }

    public function reset()
    {
        StatesLocalCache::reset();
        $this->stateManager->reset($this->serviceManager->gameService->getGame());
        $str_this_controller_kebab_name = ReflectionUtils::getKebabClassName($this, 'Controller');
        return redirect()->route($str_this_controller_kebab_name);
    }
}
