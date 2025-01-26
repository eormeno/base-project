<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class BouncingBallRoot extends Prefab
{

	public static function structure(): array
	{
		return [
			'states' => self::states(),
			'initial_view:bba.initial-view-prefab' => [],
			'object_1' => [
				'components' => ['label' => ['text' => '1']],
				'object_1_1' => [
					'object_1_1_1' => ['components' => ['label' => ['text' => '1.1.1']]],
					'object_1_1_2' => ['components' => ['label' => ['text' => '1.1.2']]],
				],
				'object_1_2' => [
					'object_1_2_1' => ['components' => ['label' => ['text' => '1.2.1']]],
					'object_1_2_2' => ['components' => ['label' => ['text' => '1.2.2']]],
				],
				'object_1_3' => [
					'object_1_3_1' => ['components' => ['label' => ['text' => '1.3.1']]],
					'object_1_3_2' => ['components' => ['label' => ['text' => '1.3.2']]],
				],
				'object_1_4' => [
					'object_1_4_1' => ['components' => ['label' => ['text' => '1.4.1']]],
					'object_1_4_2' => ['components' => ['label' => ['text' => '1.4.2']]],
				],
			],
		];
	}

	private static function states(): array
	{
		return [
			'initial' => ['bba.initial-state' => []],
			'start' => ['bba.starting-state' => []],
			'playing' => ['bba.playing-state' => []],
			'game_over' => ['bba.game-over-state' => []],
		];
	}
}
