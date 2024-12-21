<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab;

class Root extends Prefab
{
    public static function structure(): array
    {
        return [
            'components' => [
                'bba.initial-state' => [],
                'sprite-renderer' => [
                    'texture' => 'background.jpeg',
                    'layer' => 0,
                ],
            ],
            'children' => [
                'bba.ball' => [],
                'slot' => []
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
