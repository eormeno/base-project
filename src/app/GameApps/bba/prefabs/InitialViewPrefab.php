<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class InitialViewPrefab extends Prefab
{
	public static function children(): array
	{
		return [
			'title' => ['prefab' => 'label', 'attributes' => ['text' => 'Bouncing Ball', 'style' => 'title']],
			'rules' => ['prefab' => 'label', 'attributes' => ['text' => 'Click the ball to score points', 'style' => 'paragraph']],
			'scores' => ['prefab' => 'label', 'attributes' => ['text' => 'Scores: 0', 'style' => 'paragraph']],
			'button' => ['prefab' => 'button', 'attributes' => ['text' => 'Start', 'event' => 'start']],
		];
	}
}
