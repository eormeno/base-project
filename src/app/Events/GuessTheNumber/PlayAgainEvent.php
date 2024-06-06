<?php

namespace App\Events\GuessTheNumber;

class PlayAgainEvent
{
    public string $event = 'play_again';
    public $data = null;
}
