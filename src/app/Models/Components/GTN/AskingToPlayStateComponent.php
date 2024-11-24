<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class AskingToPlayStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_asking_to_play_state_components';
    protected $view_name = 'guess-the-number.asking-to-play';
    protected $state = 'asking-to-play';

    protected $fillable=[
        'messages',
    ];
    protected $casts = [
        'messages' => 'array',
    ];

    public string $description = "";
    public string $yes_i_accept_the_challenge = "";
    public array $ranking = [];

    public function awake(): void
    {
        $this->messages = $this->messageService->getMessages(self::class);
        $this->save();
    }

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
