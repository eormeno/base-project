<?php

namespace App\GameApps\mtq\prefabs;

use App\Models\Prefab\Prefab;

class Tile extends Prefab
{

	public static function structure(): array
	{
		return [
			'states' => [
				'hidden' => ['mtq.tile-hidden-state' => []],
				'revealed' => ['mtq.tile-revealed-state' => []],
			],
			'components' => ['mtq.tile' => []]
		];
	}
}
