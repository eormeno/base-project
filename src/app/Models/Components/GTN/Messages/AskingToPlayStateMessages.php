<?php

namespace App\Models\Components\GTN\Messages;

use App\Contracts\IMessageProvider;

class AskingToPlayStateMessages implements IMessageProvider
{
    public function getMessages(array $parameters): array
    {
        return [
            'description' => "BIENVENIDO A GUESS THE NUMBER!",
            'yes_button' => "Si acepto",
            'ranking' => [
                ['name' => 'Jugador 1', 'score' => 100],
                ['name' => 'Jugador 2', 'score' => 90],
                ['name' => 'Jugador 3', 'score' => 80],
                ['name' => 'Jugador 4', 'score' => 70],
                ['name' => 'Jugador 5', 'score' => 60],
            ],
        ];
    }
}
