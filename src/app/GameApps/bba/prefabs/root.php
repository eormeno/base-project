<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class Root extends Prefab
{
	public static function states(): array
	{
		return [
			'initial' => ['bba.playing-screen' => []],
		];
	}
}
