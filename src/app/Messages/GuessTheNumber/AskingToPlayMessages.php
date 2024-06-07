<?php

namespace App\Messages\GuessTheNumber;

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

}
