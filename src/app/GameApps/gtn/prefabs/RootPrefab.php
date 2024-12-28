<?php

namespace App\GameApps\gtn\prefabs;

use App\Models\Prefab;

class RootPrefab extends Prefab
{
	public static function states(): array
	{
		return [
			'initial' => ['gtn.initial-state-view' => ['view' => 'initial_view_2']],
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
				'prefab' => 'ui-container',
				'active' => false,
				'attributes' => [
					'apellido' => 'Ormeño'
				],
			],
			'showing_view' => [
				'active' => false,
			],
			'playing_view' => [
				'active' => false,
			],
			'success_view' => [
				'active' => false,
			],
			'gameover_view' => [
				'active' => false,
			],
		];
	}

	public static function structure(): array
	{
		return [
			'components' => [
				'gtn.initial-state-view' => [],
				'gtn.preparing-state' => [],
				'gtn.showing-clue-state' => [],
				'gtn.playing-state' => [],
				'gtn.success-state' => [],
				'gtn.game-over-state' => [],
			],
			'children' => [
				'initial_view_2' => [
					'prefab' => 'ui-container',
					'active' => false,
					'attributes' => [
						'apellido' => 'Ormeño'
					],
				],
				'showing_view' => [
					'active' => false,
				],
				'playing_view' => [
					'active' => false,
				],
				'success_view' => [
					'active' => false,
				],
				'gameover_view' => [
					'active' => false,
				],
			]


			// 'children' => [
			//     [
			//         'name' => 'Child 1',
			//         'active' => false,
			//         'components' => [
			//             'gtn.game-data' => [],
			//         ],
			//         'children' => [
			//             [
			//                 'name' => 'Grandchild 1.1',
			//                 'components' => [
			//                     'gtn.game-data' => [],
			//                 ],
			//             ],
			//         ],
			//     ],
			//     [
			//         'name' => 'Child 2',
			//         'components' => [
			//             'gtn.game-data' => [],
			//         ],
			//         'children' => [
			//             [
			//                 'name' => 'Grandchild 2.1',
			//                 'components' => [
			//                     'gtn.game-data' => [],
			//                 ],
			//             ],
			//             [
			//                 'name' => 'Grandchild 2.2',
			//                 'components' => [
			//                     'gtn.game-data' => [],
			//                 ],
			//             ],
			//         ],
			//     ],
			// ],
		];
	}
}
