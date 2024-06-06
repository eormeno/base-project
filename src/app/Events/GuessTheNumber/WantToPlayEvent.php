<?php

namespace App\Events\GuessTheNumber;

class WantToPlayEvent
{
    public string $event = 'want_to_play';
    public $data = null;
}
