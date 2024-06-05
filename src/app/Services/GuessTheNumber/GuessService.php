<?php

namespace App\Services\GuessTheNumber;

class GuessService extends AbstractGuessService
{
    public function guess($number)
    {
        $this->checkNumberIsCheat($number);
        $this->checkNumberOutOfRange($number);
        $this->checkNumberIsGuessed($number);
        $this->checkNumberIsLowerThanRandomNumber($number);
        $this->checkNumberIsGreaterThanRandomNumber($number);
    }

}
