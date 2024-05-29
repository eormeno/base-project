<?php

namespace App\Http\Controllers\GuessTheNumber\Logics;

use App\Http\Controllers\GuessTheNumber\Globals;

trait PlayingLogics
{
    private function isNumberCheat($number, $random_number): bool
    {
        if ($number == Globals::CHEAT_NUMBER) {
            $this->infoToast($this->cheatMessage($random_number));
            return true;
        }
        return false;
    }

    private function isNumberNotBetween($number): bool
    {
        if ($number < Globals::MIN_NUMBER || $number > Globals::MAX_NUMBER) {
            $this->errorToast($this->invalidNumberMessage());
            return true;
        }
        return false;
    }

    private function noEnoughAttempts($remaining_attempts, $state_class): bool
    {
        if ($remaining_attempts <= 1) {
            $this->context->setState($state_class);
            return true;
        }
        return false;
    }

    private function isNumberLowerThanRandomNumber($number, $random_number): bool
    {
        if ($number < $random_number) {
            $grather_message = $this->greaterMessage($number);
            $this->warningToast($grather_message);
            return true;
        }
        return false;
    }

    private function isNumberGreaterThanRandomNumber($number, $random_number): bool
    {
        if ($number > $random_number) {
            $lower_message = $this->lowerMessage($number);
            $this->warningToast($lower_message);
            return true;
        }
        return false;
    }

    private function isNumberGuessed($number, $random_number, $state_class): bool
    {
        if ($number == $random_number) {
            $this->context->setState($state_class);
            return true;
        }
        return false;
    }

}
