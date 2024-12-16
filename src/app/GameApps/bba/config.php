<?php

return [
    'image' => 'bouncing-ball.jpeg',
    'name' => 'Bouncing Ball Arena',
    'active' => true,
    'description' => 'A game where players must bounce a ball into a goal.',
    'client' => 'webgl',
    'width' => 800,
    'height' => 450,
    'prefab_name' => 'bba.root',
    'states' => [
        'presentation_screen' => [
            'on' => 'initial',
            'triggers' => ['display_loading_screen']
        ],
        'loading_screen' => [
            'on' => 'display_loading_screen',
            'triggers' => ['load_complete']
        ],
        'main_menu' => [
            'on' => 'load_complete',
            'triggers' => [
                'select_option' => [
                    'create_new_game' => 'create_new_game',
                    'play_with_ia' => 'play_with_ia',
                    'view_credits' => 'credits',
                    'view_best_scores' => 'best_scores'
                ],
            ]
        ],
        'create_new_game' => [
            'on' => 'create_new_game',
            'triggers' => ['game_created', 'go_back' => 'main_menu']
        ],
        'send_invitation_code' => [
            'on' => 'game_created',
            'triggers' => ['code_sent', 'go_back' => 'create_new_game']
        ],
        'waiting_opponent' => [
            'on' => 'code_sent',
            'triggers' => ['opponent_joined', 'go_back' => 'send_invitation_code']
        ],
        'play_with_ia' => [
            'on' => 'play_with_ia',
            'triggers' => ['start_ai_game', 'go_back' => 'main_menu']
        ],
        'playing' => [
            'on' => ['start_ai_game', 'opponent_joined'],
            'triggers' => ['you_win', 'you_lost']
        ],
        'you_win' => [
            'on' => 'you_win',
            'triggers' => ['go_back' => 'main_menu']
        ],
        'you_lost' => [
            'on' => 'you_lost',
            'triggers' => ['go_back' => 'main_menu']
        ],
        'disconnected' => [
            'on' => 'connection_lost',
            'triggers' => ['reconnect', 'go_back' => 'main_menu']
        ],
        'session_expired' => [
            'on' => 'session_timeout',
            'triggers' => ['session_renewed', 'go_back' => 'main_menu']
        ],
        'credits' => [
            'on' => 'view_credits',
            'triggers' => ['go_back' => 'main_menu']
        ],
        'best_scores' => [
            'on' => 'view_best_scores',
            'triggers' => ['go_back' => 'main_menu']
        ]
    ]
];
