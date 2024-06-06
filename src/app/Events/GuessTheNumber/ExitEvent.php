<?php

namespace App\Events\GuessTheNumber;

class ExitEvent
{
    public string $event = 'exit';
    public $data = null;
}
