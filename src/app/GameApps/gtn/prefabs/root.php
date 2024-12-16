<?php

return [
    'states' => [
        'initial_state_view' => [
            'on' => 'initial',
            'triggers' => ['start_preparing']
        ],
        'preparing_state' => [
            'on' => 'start_preparing',
            'auto_transition' => 'showing_clues'
        ],
        'showing_clue_state' => [
            'on' => 'show_clues',
            'triggers' => [
                'other_challenge' => 'preparing',
                'accept_challenge' => 'playing'
            ]
        ],
        'playing_state' => [
            'on' => 'accept_challenge',
            'triggers' => [
                'success' => 'success',
                'game_over' => 'game_over',
                'repeat' => 'playing'
            ]
        ],
        'success_state' => [
            'on' => 'success',
            'triggers' => [
                'show_clues' => 'showing_clues'
            ],
            'auto_transition_after' => [
                'time' => 5,
                'state' => 'asking_to_play'
            ]
        ],
        'game_over_state' => [
            'on' => 'game_over',
            'triggers' => [
                'show_clues' => 'showing_clues'
            ],
            'auto_transition_after' => [
                'time' => 5,
                'state' => 'asking_to_play'
            ]
        ]
    ],
    'components' => [
        // 'gtn.game-data' => [
        //     'min_number' => 1,
        //     'max_number' => 1024,
        //     'attempts' => 0,
        //     'max_attempts' => 10,
        //     'score' => 0,
        // ],
        'gtn.initial-state-view' => [],
        'gtn.game-over-state' => [],
        'gtn.playing-state' => [],
        'gtn.preparing-state' => [],
        'gtn.showing-clue-state' => [],
        'gtn.success-state' => [],
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
