<?php

namespace App\Http\Controllers\GuessTheNumber\Messages;

trait AskingToPlayMessages
{
    public function welcomeMessage()
    {
        return __('guess-the-number.description', [
            'user_name' => $this->context->userRepository->name(),
            'remaining_attemts' => $this->context->gameConfigService->maxAttempts(),
            'min_number' => $this->context->gameConfigService->getMinNumber(),
            'max_number' => $this->context->gameConfigService->getMaxNumber()
        ]);
    }
}
