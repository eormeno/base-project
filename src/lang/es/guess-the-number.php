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

    'remaining' => 'Te quedan :remaining_attemts intentos.',
    'last_attempt' => '¡Último intento! ¡Buena suerte!',
    'remaining_half' => '¡Tienes menos de la mitad! Te quedan :remaining_attemts intentos.',
    'starting_attempts' => '¡Comenzamos! Tienes :remaining_attemts intentos.',
    'enter_number' => 'Adivina el número',
    'submit' => 'Adivinar',
    'message' => 'Mensaje',
    'greater' => 'El número es mayor que :number',
    'lower' => 'El número es menor que :number',
    'success' => '¡Has adivinado el número :user_name!',
    'game-over' => '¡Game Over! ¡Se acabaron los intentos :user_name!',
    'play-again' => '¡Quiero jugar de nuevo!',
    'reset' => 'Reiniciar',
    'invalid_number' => 'Número inválido. Por favor, introduce un número entre :min_number y :max_number.',
    'cheat' => '¡Has hecho trampa! El número era :random_number',
    'game-over-subtitle' => 'El número secreto era :random_number',
    'success-subtitle' => 'Lo adivinaste en :attempts intentos.',
    'current-score' => 'Tu puntuación actual es :score',
    'historic-score' => 'Tu puntuación histórica es :score',
    'exit' => 'Salir al menú principal',
];
