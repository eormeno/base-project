<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use App\Events\FrontEvent;
use App\Services\GameInstanceService;
use App\Services\StateContextService;
use App\Http\Requests\EventRequestFilter;

class GameAppController extends Controller
{
    public function play(
        GameApp $gameApp,
        GameInstanceService $gamesService
    ) {
        $currentGame = $gamesService->lastGameInstanceOfUser($gameApp);
        return view('game-app.index', compact('currentGame'));
    }

    public function event(
        Game $game,
        EventRequestFilter $request,
        StateContextService $context
    ) {
        event(new FrontEvent($game, $request->eventInfo()));
        return response()->json(
            $context->request($game, $request->eventInfo())
        );
    }
}
