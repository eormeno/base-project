<?php

namespace App\Services\GuessTheNumber\MessageComponents;

use App\Services\GuessTheNumber\AbstractComponent;

class AskingToPlayMessages extends AbstractComponent
{
    public function welcomeMessage()
    {
        return __('guess-the-number.description', [
            'user_name' => $this->userRepository->name(),
            'remaining_attemts' => $this->gameConfigService->getMaxAttempts(),
            'min_number' => $this->gameConfigService->getMinNumber(),
            'max_number' => $this->gameConfigService->getMaxNumber()
        ]);
    }

    public function yesIAcceptTheChallenge()
    {
        return __('guess-the-number.want-to-play');
    }

}
