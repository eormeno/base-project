<?php

namespace App\GameApps\gtn\Services;

use App\Models\GameService;

class GuessService extends GameService
{
    protected $table = null;

    protected GtnService $gtnService;

    public function guess($number): array
    {
        $ret = [];
        $this->gtnService = $this->getService('gtn-service');
        $random_number = $this->gtnService->random_number;
        $remaining_attempts = $this->gtnService->remaining_attempts;
        $min_number = $this->gtnService->min_number;
        $max_number = $this->gtnService->max_number;
        $cheat_number = $this->gtnService->cheat_number;
        $this->checkNumberIsCheat($number, $cheat_number, $ret);
        $this->checkNumberOutOfRange($number, $min_number, $max_number, $ret);
        $this->checkNumberIsGuessed($number, $random_number, $ret);
        $this->checkNumberIsLowerThanRandomNumber($number, $ret);
        $this->checkNumberIsGreaterThanRandomNumber($number, $ret);
        return $ret;
    }

    private function checkNumberIsCheat($number, $cheat_number, array &$ret): void
    {
        if ($number == $cheat_number) {
            $ret['cheat'] = [$number];
            $this->gtnService->cheat();
        }
    }

    private function checkNumberOutOfRange($number, $min, $max, array &$ret): void
    {
        if ($number < $min || $number > $max) {
            $ret['out_of_range'] = [$number, $min, $max];
        }
    }

    private function checkNumberIsGuessed($number, $random_number, array &$ret)
    {
        if ($number == $random_number) {
            $ret['success'] = [$number];
        }
    }

    private function checkNoEnoughAttempts(array &$ret)
    {
        if ($this->gtnService->remaining_attempts == 0) {
            $ret['game_over'] = [];
        }
    }

    protected function checkNumberIsLowerThanRandomNumber($number, $random_number, $remaining_attempts, array &$ret)
    {
        if ($number < $this->gtnService->random_number) {
            $this->gtnService->decreaseRemainingAttempts();
            $this->checkNoEnoughAttempts($noEnoughAttemptsCallback);
            $ret['greater'] = [$number];
        }
    }

    protected function checkNumberIsGreaterThanRandomNumber($number, array &$ret)
    {
        if ($number > $this->gtnService->random_number) {
            $this->gtnService->decreaseRemainingAttempts();
            $this->checkNoEnoughAttempts($noEnoughAttemptsCallback);
            $ret['lower'] = [$number];
        }
    }
}
