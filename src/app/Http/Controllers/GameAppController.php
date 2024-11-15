<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameApp;

class GameAppController extends Controller
{
    public function index()
    {
        $games = GameApp::all();
        return response()->json($games);
    }

    public function show(GameApp $gameApp)
    {
        return response()->json($gameApp);
    }
}
