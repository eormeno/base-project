<?php

return [

    'mtq' => [
        'title' => 'La Búsqueda del Tesoro Mítico',
        'root' => 'game',
        'game' => [
            'states' => [
                'flagging' => [],
                'game-over' => [],
                'initial' => [],
                'playing' => [
                    'map' => ['map'],
                    'inventory' => ['inventory'],
                ],
            ]
        ],
        'map' => [
            'states' => [
                'map-displaying' => [
                    'w' => ['integer', 'default' => 8],
                    'h' => ['integer', 'default' => 8],
                    'tiles' => ['tile', 'many']
                ],
            ],
        ],
        'inventory' => [
            'states' => [
                'inventory-displaying' => ['items', 'many'],
            ],
        ],
        'tile' => [
            'states' => [
                'flagged-tile' => [],
                'flagging-tile' => [],
                'gameOver-tile' => [],
                'hidden' => ['marked_as_clue', 'marked_as_flag'],
                'revealed' => ['has_trap', 'traps_around'],
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
                'asking-to-play' => [],
                'game-over' => [],
                'initial' => [],
                'playing' => [],
                'preparing' => [],
                'showing-clue' => [],
                'success' => [],
            ],
        ],
    ],
];
