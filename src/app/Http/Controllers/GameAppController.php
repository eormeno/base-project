<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use App\Models\Components\WebRenderizable;

class GameAppController extends Controller
{
    public function play(GameApp $gameApp)
    {
        $count = $this->countGameInstances($gameApp);

        if ($count < $gameApp->max_instances_per_user) {
            $newGameInstance = Game::create([
                'game_app_id' => $gameApp->id,
                'game_object_id' => $gameApp->prefab->instantiate()->id,
                'invitation_code' => uniqid(),
            ]);
            $newGameInstance->players()->attach(auth()->user());
        }

        if ($gameApp->max_instances_per_user === 1) {
            $currentGame = $gameApp->games->first();
            $gameObject = $currentGame->gameObject;
            foreach ($gameObject->components as $component) {
                $subclass = $component->subclass();
                if ($subclass instanceof WebRenderizable) {
                    $component->view = $subclass->view();
                }
            }

            //return response()->json($gameObject);
            $routeName = 'guess-the-number';
            return view('game-app.index', compact('gameObject', 'routeName'));
        }

        return response()->json([
            'message' => 'Should be able to select a game instance',
        ]);
    }

    private function countGameInstances(GameApp $gameApp)
    {
        return auth()->user()->games()->where('game_app_id', $gameApp->id)->count();
    }
}
