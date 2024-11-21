<?php

namespace App\Models\Components\GTN;

use App\Models\Components\IState;
use App\Models\Components\WebRendererComponent;

class AskingToPlayComponent extends WebRendererComponent implements IState
{
    protected $view_name = 'guess-the-number.asking-to-play';

    public string $description = "Esta es la descripción.";
    public string $yes_i_accept_the_challenge = "Si acepto";
    public array $ranking = [];

    public function onWantToPlayEvent()
    {
    }

    public function onEnter(): void
    {
        $this->description = "Bienvenido a Guess The Number!";
        $this->yes_i_accept_the_challenge = "Si acepto";
        $this->ranking = [
            ['name' => 'Jugador 1', 'score' => 100],
            ['name' => 'Jugador 2', 'score' => 90],
            ['name' => 'Jugador 3', 'score' => 80],
            ['name' => 'Jugador 4', 'score' => 70],
            ['name' => 'Jugador 5', 'score' => 60],
        ];
    }

    public function onExit(): void
    {
    }

}
