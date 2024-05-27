<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Preparing extends StateAbstractImpl
{
    public function handleRequest(?string $event = null, $data = null)
    {
        $this->context->random_number = rand(Globals::MIN_NUMBER, Globals::MAX_NUMBER);
        $this->context->remaining_attempts = Globals::maxAttempts();
        $this->context->setState(Playing::class);
    }
}
