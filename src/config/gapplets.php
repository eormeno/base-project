<?php

return [
    'mtq' => [
        'title' => 'Mythic Treasure Quest',
        'root' => 'MtqGame',
        'MtqGame' => [
            'states' => [
                'Initial',
                'Playing',
                'Flagging',
                'GameOver',
            ],
        ],
        'MtqMap' => [
            'states' => [
                'Initial'
            ],
        ],
        'MtqInventory' => [
            'states' => [
                'Initial'
            ],
        ],
        'MtqTile' => [
            'states' => [
                'Initial'
            ],
        ],
        'MtqItem' => [
            'states' => [
                'Initial'
            ],
        ],
    ]
];
