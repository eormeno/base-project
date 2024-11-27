<?php

return [
    'state' => null,
    'components' => [
        'gtn.game-data' => [
            'min_number' => 1,
            'max_number' => 1024,
            'attempts' => 0,
            'max_attempts' => 10,
            'score' => 0,
        ],
        'gtn.initial-state-view' => [],
        'gtn.game-over-state' => [],
        'gtn.playing-state' => [],
        'gtn.preparing-state' => [],
        'gtn.showing-clue-state' => [],
        'gtn.success-state' => [],
    ]
];
