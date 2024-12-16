<?php

return [
    'image' => 'guess-the-number.jpeg',
    'name' => 'Adivina el número',
    'description' => 'Un simple juego donde adivinas un número entre 1 y 1024.',
    'prefab_name' => 'gtn.root',
    'client' => 'blade',
    'states' => [
        'asking_to_play' => [
            'on' => 'initial',
            'triggers' => ['start_preparing']
        ],
        'preparing' => [
            'on' => 'start_preparing',
            'auto_transition' => 'showing_clues'
        ],
        'showing_clues' => [
            'on' => 'show_clues',
            'triggers' => [
                'other_challenge' => 'preparing',
                'accept_challenge' => 'playing'
            ]
        ],
        'playing' => [
            'on' => 'accept_challenge',
            'triggers' => [
                'success' => 'success',
                'game_over' => 'game_over',
                'repeat' => 'playing'
            ]
        ],
        'success' => [
            'on' => 'success',
            'triggers' => [
                'show_clues' => 'showing_clues'
            ],
            'auto_transition_after' => [
                'time' => 5,
                'state' => 'asking_to_play'
            ]
        ],
        'game_over' => [
            'on' => 'game_over',
            'triggers' => [
                'show_clues' => 'showing_clues'
            ],
            'auto_transition_after' => [
                'time' => 5,
                'state' => 'asking_to_play'
            ]
        ]
    ]
];
