<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use App\Http\Requests\EventRequestFilter;

class GameAppController extends Controller
{
    public function play(GameApp $gameApp)
    {
        $currentGame = $this->lastGameInstanceOfUser($gameApp);
        // return response()->json(['game' => $currentGame,]);
        // return view('game-app.index', compact('currentGame'));
        return $currentGame->gameObject->view();
    }

    public function event(Game $game, EventRequestFilter $request)
    {
        return response()->json([
            'game' => $game->id,
            'event' => $request->eventInfo(),
        ]);
    }

    private function countUserGameInstances(GameApp $gameApp)
    {
        return auth()->user()->games()->where('game_app_id', $gameApp->id)->count();
    }

    private function lastGameInstanceOfUser(GameApp $gameApp): Game
    {
        $count = $this->countUserGameInstances($gameApp);

        if ($count < $gameApp->max_instances_per_user) {
            dd("Creating new game instance");
            Game::create([
                'game_app_id' => $gameApp->id,
                'game_object_id' => $gameApp->prefab->instantiate()->id,
                'invitation_code' => uniqid(),
            ])->players()->attach(auth()->user());
        }
        $currentGame = auth()->user()->games()->where('game_app_id', $gameApp->id)->first();
        $currentGame->gameObject;
        $currentGame->title = $gameApp->name;
        $currentGame->description = $gameApp->description;

        return $currentGame;
    }
}
