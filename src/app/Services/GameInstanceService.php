<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameApp;

class GameInstanceService
{
    public function countUserGameInstances(GameApp $gameApp)
    {
        return auth()->user()->games()->where('game_app_id', $gameApp->id)->count();
    }

    public function lastGameInstanceOfUser(GameApp $gameApp): Game
    {
        $count = $this->countUserGameInstances($gameApp);
        if ($count < $gameApp->max_instances_per_user) {
            Game::factory()->forGameApp($gameApp)->forAuthUser()->withServices()->create();
        }
        $currentGame = auth()->user()->games()->where('game_app_id', $gameApp->id)->first();
        $currentGame->gameObject;
        $currentGame->title = $gameApp->name;
        $currentGame->description = $gameApp->description;
        return $currentGame;
    }
}
