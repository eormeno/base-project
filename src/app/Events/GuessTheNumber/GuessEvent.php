<?php

namespace App\Events\GuessTheNumber;

class GuessEvent
{
    public string $event = 'guess';
    public $data = null;
}
