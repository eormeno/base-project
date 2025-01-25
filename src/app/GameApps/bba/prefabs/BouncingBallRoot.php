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

	public static function children(): array
	{
		return [
			'initial_view' => ['prefab' => 'bba.initial-view-prefab'],
			'object_1' => [
				'object_1_1' => [
					'object_1_1_1' => ['components' => ['label' => []]],
					'object_1_1_2' => [],
				],
				'object_1_2' => [
					'object_1_2_1' => [],
					'object_1_2_2' => [],
				],
				'object_1_3' => [
					'object_1_3_1' => [],
					'object_1_3_2' => [],
				],
				'object_1_4' => [
					'object_1_4_1' => [],
					'object_1_4_2' => [],
				],
			],
		];
	}
}
