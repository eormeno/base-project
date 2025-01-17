<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class InitialViewPrefab extends Prefab
{
	public static function children(): array
	{
		return [
			'title' => ['prefab' => 'label', 'attributes' => ['text' => 'Bouncing Ball']],
			'rules' => ['prefab' => 'label'],
			'scores' => ['prefab' => 'label'],
			'button' => ['prefab' => 'button'],
		];
	}
}
