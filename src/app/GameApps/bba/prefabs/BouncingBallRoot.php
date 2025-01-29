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

			// 'object_1:container' => [
			// 	'object_1_1:container' => [
			// 		'attributes' => ['layout' => 'horizontal'],
			// 		'object_1_1_1:label' => ['attributes' => ['text' => '1.1.1', 'style' => 'normal']],
			// 		'object_1_1_2:label' => ['attributes' => ['text' => '1.1.2', 'style' => 'normal']],
			// 		'object_1_1_3:button' => ['attributes' => ['text' => 'Button 1.1.3', 'event'=>'start']],
			// 	],
			// 	'object_1_2:container' => [
			// 		'object_1_2_1:label' => ['attributes' => ['text' => '1.2.1', 'style' => 'heading-1']],
			// 		'object_1_2_2:label' => ['attributes' => ['text' => '1.2.2', 'style' => 'heading-1']],
			// 	],
			// 	'object_1_3:container' => [
			// 		'object_1_3_1:label' => ['attributes' => ['text' => '1.3.1', 'style' => 'heading-2']],
			// 		'object_1_3_2:label' => ['attributes' => ['text' => '1.3.2', 'style' => 'heading-2']],
			// 	],
			// 	'object_1_4:container' => [
			// 		'object_1_4_1:label' => ['attributes' => ['text' => '1.4.1', 'style' => 'heading-3']],
			// 		'object_1_4_2:label' => ['attributes' => ['text' => '1.4.2', 'style' => 'heading-3']],
			// 	],
			// ],
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
