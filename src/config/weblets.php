<?php

return [

    'mtq' => [
        'title' => 'La Búsqueda del Tesoro Mítico',
        'root' => 'game',

        // 'game' => [
        //     'state' => 'initial',
        //     1 => ['state_renderer' => ['on_state' => 'initial']],
        //     2 => ['state_renderer' => ['on_state' => 'game_over']],
        //     3 => ['playing_renderer' => ['map' => 'map', 'inventory' => 'inventory']],
        // ],

        // 'map' => [
        //     'state' => 'initial',
        //     0 => ['map_config' => ['rows' => 8, 'columns' => 8, 'mines' => 8, 'tiles' => 'tile']],
        //     1 => ['state_renderer' => ['on_state' => 'initial']],
        // ],

        // 'inventory' => [
        //     'state' => 'initial',
        //     0 => ['inventory_config' => ['selected_item' => null, 'items' => 'item']],
        //     1 => ['state_renderer' => ['on_state' => 'initial']],
        // ],

        // 'tile' => [
        //     'state' => 'hidden',
        //     0 => ['tile_config' => []],
        //     1 => ['state_renderer' => ['on_state' => 'hidden']],
        //     2 => ['state_renderer' => ['on_state' => 'flagged-tile']],
        //     3 => ['state_renderer' => ['on_state' => 'flagging-tile']],
        //     4 => ['state_renderer' => ['on_state' => 'gameOver-tile']],
        //     5 => ['state_renderer' => ['on_state' => 'revealed']],
        // ],

        'item' => [
            'state' => 'initial',
            0 => ['item_config' => []],
            1 => ['state_renderer' => ['on_state' => 'initial']],
        ],
    ],

    'gtn' => [
        'title' => 'Guess The Number',
        'root' => 'game',
        'game' => [
            'state' => 'initial',
            0 => ['guess_the_number' => ['max_attempts' => 10, 'min_number' => 1, 'max_number' => 1024,]],
            1 => ['state_renderer' => ['on_state' => 'initial']],
            2 => ['state_renderer' => ['on_state' => 'asking_to_play']],
            3 => ['state_renderer' => ['on_state' => 'game_over']],
            4 => ['state_renderer' => ['on_state' => 'playing']],
            5 => ['state_renderer' => ['on_state' => 'preparing']],
            6 => ['state_renderer' => ['on_state' => 'showing_clue']],
            7 => ['state_renderer' => ['on_state' => 'success']],
        ],
    ],
];
