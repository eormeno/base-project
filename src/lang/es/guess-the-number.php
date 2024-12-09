<?php

return [

    'initial' => [
        'description' => "¡Hola :user_name! Pensaré un número entre :min_number y :max_number. Tienes :max_attempts intentos para adivinarlo. ¿Aceptas el reto?",
        'yes_button' => '¡Si, acepto el reto!',
        'ranking_title' => 'Mejores puntuaciones',
    ],
    'showing-clue' => [
        'title' => '¡Adivina el número!',
        'good_luck' => '¡Buena suerte!',
        'yes_button' => '¡Si, acepto el reto!',
        'another_challenge' => 'No, quiero otro reto',
        'clue' => [
            'iterations' => 'Puedes resolverlo en :data intentos',
            'prime' => '¡El número es primo!',
            'multiples' => 'Es múltiplo de: :data',
            'even' => 'Es un número par',
            'odd' => 'Es un número impar',
        ]
    ],
    'playing' => [
        'enter_number_message' => 'Adivina el número',
        'enter_number_button' => 'Adivinar',
        'remaining_message' => [
            'starting' => '¡Comenzamos! Tienes :remaining_attempts intentos.',
            'remaining' => 'Te quedan :remaining_attempts intentos.',
            'half' => '¡Tienes menos de la mitad! Te quedan :remaining_attempts intentos.',
            'last' => '¡Último intento! ¡Buena suerte!',
            'finished' => 'Fin del juego',
        ],
        'guess_result' => [
            'greater' => 'El número es mayor que :0',
            'lower' => 'El número es menor que :0',
            'cheat' => '¡Has hecho trampa! El número era :0',
            'out_of_range' => 'Número inválido. Por favor, introduce un número entre :1 y :2.',
            'success' => '¡Has adivinado el número :0!',
            'game_over' => '¡Game Over! ¡Se acabaron los intentos! El número era :0',
        ]
    ],
    'game-over' => [
        'notification' => '¡Game Over! ¡Se acabaron los intentos :user_name!',
        'subtitle' => 'El número secreto era :random_number',
        'play_again' => '¡Quiero jugar de nuevo!',
        'exit' => 'Salir al menú principal',
    ],
    'success'=>[
        'notification' => '¡Has adivinado el número :user_name!',
        'subtitle' => 'Lo adivinaste en :attempts intentos.',
        'play_again' => '¡Quiero jugar de nuevo!',
        'current_score' => 'Tu puntuación actual es :score',
        'historic_score' => 'Tu puntuación histórica es :hscore',
        'exit' => 'Salir al menú principal',
    ],

    'message' => 'Mensaje',
    'reset' => 'Reiniciar',
];
