<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use App\Services\GameInstanceService;
use App\Http\Requests\EventRequestFilter;
use App\Services\StateContextService;

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
        return response()->json(
            $context->request($game, $request->eventInfo())
        );
    }
}
