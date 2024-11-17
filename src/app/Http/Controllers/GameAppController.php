<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameApp;

class GameAppController extends Controller
{
    public function play(GameApp $gameApp)
    {
        // count from the current user, the number of games that it has playing
        $count = auth()->user()->games()->where('game_app_id', $gameApp->id)->count();
        if ($count >= $gameApp->max_instances_per_user) {
            return response()->json(['message' => 'You have reached the maximum number of instances']);
        }
        $gameApp->prefab; // eager loading the prefab
        // add the $count to the gameApp object for the response
        $gameApp->user_instances = $count;

        return response()->json($gameApp);
    }
}
