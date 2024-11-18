<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameApp;
use Illuminate\Http\Request;

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
            $gameApp->prefab; // eager loading the prefab
            return response()->json($gameApp);
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
