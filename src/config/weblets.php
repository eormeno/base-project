<?php

return [

    'mtq' => [
        'title' => 'La Búsqueda del Tesoro Mítico',
        'root' => 'game',
        'game' => [
            'states' => [
                'flagging',
                'game-over',
                'initial',
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
                'asking-to-play',
                'game-over',
                'playing',
                'preparing',
                'showing-clue',
                'success',
            ],
        ],
    ],
];
