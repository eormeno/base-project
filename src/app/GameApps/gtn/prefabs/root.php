<?php

return [
    'components' => [
        'gtn.initial-state-view' => [
            // 'listen_to' => ['init', 'wants_to_play'],
            // 'trigger' => 'start_preparing',
            // 'view' => 'gtn.initial-state-view',
        ],
        'gtn.preparing-state' => [
            // 'listen_to' => 'start_preparing',
            // 'auto_trigger' => 'show_clue',
            // 'view' => 'gtn.preparing-state-view',
        ],
        'gtn.showing-clue-state' => [
            // 'listen_to' => [
            //     'show_clue',
            //     'other_challenge',
            // ],
            // 'trigger' => [
            //     'other_challenge',
            //     'accept_challenge',
            // ],
            // 'view' => 'gtn.showing-clue-state-view',
        ],
        'gtn.playing-state' => [
            // 'listen_to' => ['accept_challenge', 'guess'],
            // 'trigger' => [
            //     'success',
            //     'game_over',
            // ],
            // 'view' => 'gtn.playing-state-view',
        ],
        'gtn.success-state' => [
            // 'listen_to' => 'success',
            // 'trigger' => [
            //     'show_clue',
            // ],
            // 'auto_trigger_after' => [5, 'asking_to_play'],
            // 'view' => 'gtn.success-state-view',
        ],
        'gtn.game-over-state' => [
            // 'listen_to' => 'game_over',
            // 'trigger' => [
            //     'show_clue',
            // ],
            // 'auto_trigger_after' => [5, 'asking_to_play'],
            // 'view' => 'gtn.game-over-state-view',
        ],
    ],

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
