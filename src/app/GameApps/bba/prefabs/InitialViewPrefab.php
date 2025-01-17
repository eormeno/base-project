<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class InitialViewPrefab extends Prefab
{
	public static function children(): array
	{
		return [
			'title' => ['prefab' => 'common.label', 'attributes' => ['text' => 'Bouncing Ball']],
			'rules' => ['prefab' => 'common.label'],
			'scores' => ['prefab' => 'common.label'],
			'button' => ['prefab' => 'common.button'],
		];
	}
}
