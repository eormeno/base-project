<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

use App\Http\Controllers\GuessTheNumber\Globals;

class GuessService
{
    private $random_number;
    private $remaining_attempts;

    public function setRandomNumber($random_number)
    {
        $this->random_number = $random_number;
    }

    public function setRemainingAttempts($remaining_attempts)
    {
        $this->remaining_attempts = $remaining_attempts;
    }

    public function guess($number)
    {
        $this->checkNumberIsCheat($number);
        $this->checkNumberIsNotBetween($number, Globals::MIN_NUMBER, Globals::MAX_NUMBER);
        $this->checkNoEnoughAttempts();
        $this->checkNumberIsLowerThanRandomNumber($number);
        $this->checkNumberIsGreaterThanRandomNumber($number);
        $this->checkNumberIsGuessed($number);
    }

    private function checkNumberIsCheat($number)
    {
        if ($number == Globals::CHEAT_NUMBER) {
            throw new CheatException();
        }
    }

    private function checkNumberIsNotBetween($number, $min, $max)
    {
        if ($number < $min || $number > $max) {
            return new NotInRangeException($number, $min, $max);
        }
    }

    private function checkNoEnoughAttempts()
    {
        if ($this->remaining_attempts <= 1) {
            return new NoEnoughAttemtsException();
        }
    }

    private function checkNumberIsLowerThanRandomNumber($number)
    {
        if ($number < $this->random_number) {
            return new LowerThanRandomException($number);
        }
    }

    private function checkNumberIsGreaterThanRandomNumber($number)
    {
        if ($number > $this->random_number) {
            return new GratherThanRandomException($number);
        }
    }

    private function checkNumberIsGuessed($number)
    {
        return $number == $this->random_number;
    }
}
