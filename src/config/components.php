<?php

return [
    'guess_the_number' => [
        'times_played' => ['type' => 'integer', 'default' => 0],
        'max_attempts' => ['type' => 'integer'],
        'half_attempts' => ['type' => 'integer'],
        'min_number' => ['type' => 'integer'],
        'max_number' => ['type' => 'integer'],
        'remaining_attempts' => ['type' => 'integer'],
        'random_number' => ['type' => 'integer'],
        'score' => ['type' => 'integer', 'default' => 0],
    ],
    'state_renderer' => [
        'on_state' => ['type' => 'string'],
    ],
    'playing_renderer' => [
        'on_state' => ['type' => 'string', 'default' => 'playing'],
        'map' => ['type' => 'game_object'],
        'inventory' => ['type' => 'game_object'],
    ],
    'map_config' => [
        'rows' => ['type' => 'integer'],
        'columns' => ['type' => 'integer'],
        'mines' => ['type' => 'integer'],
        'tiles' => ['type' => 'game_object', 'multiple' => true],
    ],

    // 'tile_config' => [
    //     'x' => ['type' => 'integer', 'default' => 0],
    //     'y' => ['type' => 'integer', 'default' => 0],
    //     'has_trap' => ['type' => 'boolean', 'default' => false],
    //     'traps_around' => ['type' => 'integer', 'default' => 0],
    //     'marked_as_clue' => ['type' => 'boolean', 'default' => false],
    //     'marked_as_flag' => ['type' => 'boolean', 'default' => false],
    // ],


    'inventory_config' => [
        'selected_item' => ['type' => 'game_object'],
        'items' => ['type' => 'game_object', 'multiple' => true],
    ],
    'item_config' => [
        'slug' => ['type' => 'string'],
        'icon' => ['type' => 'string'],
        'name' => ['type' => 'string'],
        'quantity' => ['type' => 'integer'],
    ],

];
