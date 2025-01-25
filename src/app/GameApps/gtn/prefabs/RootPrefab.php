<?php

namespace App\GameApps\gtn\prefabs;

use App\Models\Prefab\Prefab;

class RootPrefab extends Prefab
{
	public static function states(): array
	{
		return [
			'initial' => ['gtn.initial-state-view' => []],
			'preparing' => ['gtn.preparing-state' => []],
			'showing_clue' => ['gtn.showing-clue-state' => []],
			'playing' => ['gtn.playing-state' => []],
			'success' => ['gtn.success-state' => []],
			'game_over' => ['gtn.game-over-state' => []],
		];
	}

	public static function components(): array
	{
		return [
			'gtn.game-data' => [],
		];
	}

	public static function children(): array
	{
		return [
			'initial_view_2' => [
				'prefab' => 'container',
				'active' => false,
				'attributes' => [
					'apellido' => 'Ormeño'
				],
			],
			'showing_view' => [],
			'playing_view' => [],
			'success_view' => [],
			'gameover_view' => [],
		];
	}
}
