<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use App\Contracts\IRenderer;
use App\Services\GameInstanceService;
use App\Http\Requests\EventRequestFilter;

class GameAppController extends Controller
{
    public function play(
        GameApp $gameApp,
        GameInstanceService $gamesService
    ) {
        $currentGame = $gamesService->lastGameInstanceOfUser($gameApp);
        return view("game-app.$gameApp->client", compact('currentGame'));
    }

    public function event(
        Game $game,
        EventRequestFilter $request,
        IRenderer $renderer,
    ) {
        return response()->json(
            $renderer->render($game, $request->eventInfo())
        );
    }
}
