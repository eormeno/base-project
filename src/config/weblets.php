<?php

return [

    'mtq' => [
        'title' => 'La Búsqueda del Tesoro Mítico',
        'root' => 'game',
        'game' => [
            'states' => [
                'initial',
                'flagging',
                'game-over',
                'playing',
            ]
        ],
        'map' => [
            'states' => [
                'map-displaying',
            ],
        ],
        'inventory' => [
            'states' => [
                'inventory-displaying',
            ],
        ],
        'tile' => [
            'states' => [
                'hidden',
                'flagged-tile',
                'flagging-tile',
                'gameOver-tile',
                'revealed',
            ],
            'x' => ['integer'],
            'y' => ['integer'],
            'has_trap' => ['boolean'],
            'traps_around' => ['integer'],
            'marked_as_clue' => ['boolean'],
            'marked_as_flag' => ['boolean'],
        ],
        'item' => [
            'states' => [
                'item-normal-state'
            ],
            'slug' => ['string'],
            'icon' => ['string'],
            'name' => ['string'],
            'quantity' => ['integer'],
        ],
    ],

    'gtn' => [
        'title' => 'Guess The Number',
        'root' => 'game',
        'game' => [
            'states' => [
                'initial',
                'asking_to_play',
                'game_over',
                'playing',
                'preparing',
                'showing_clue',
                'success',
            ],
            'times_played' => ['integer'],
            'max_attempts' => ['integer'],
            'half_attempts' => ['integer'],
            'min_number' => ['integer'],
            'max_number' => ['integer'],
            'remaining_attempts' => ['integer'],
            'random_number' => ['integer'],
            'score' => ['integer'],
        ],
    ],
];
