<?php

namespace App\Services\GuessTheNumber;

class GuessService extends AbstractGuessService
{
    public function guess($number)
    {
        $this->checkNumberIsCheat($number);
        $this->checkNumberIsNotBetween($number);
        $this->checkNumberIsGuessed($number);
        $this->checkNoEnoughAttempts();
        $this->checkNumberIsLowerThanRandomNumber($number);
        $this->checkNumberIsGreaterThanRandomNumber($number);
    }

}
