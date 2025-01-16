<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class BouncingBallRoot extends Prefab
{
	public static function states(): array
	{
		return [
			'initial' => ['bba.initial-state' => []],
			'start' => ['bba.starting-state' => []],
			'playing' => ['bba.playing-state' => []],
			'game_over' => ['bba.game-over-state' => []],
		];
	}
}
