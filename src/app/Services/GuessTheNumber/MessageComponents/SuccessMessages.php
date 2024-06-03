<?php

namespace App\Services\GuessTheNumber\MessageComponents;

use App\Services\GuessTheNumber\AbstractComponent;

class SuccessMessages extends AbstractComponent
{

    public function successMessage(): string
    {
        return __('guess-the-number.success', [
            'user_name' => $this->userRepository->name()
        ]);
    }

    public function successSubtitleMessage(): string
    {
        $game = $this->gameRepository->getGame();
        $number = ($this->gameConfigService->getMaxAttempts() - $game->remaining_attempts) + 1;
        return __('guess-the-number.success-subtitle', [
            'attempts' => $number,
        ]);
    }

    public function currentScoreMessage(): string
    {
        return __('guess-the-number.current-score', [
            'score' => $this->gameService->calculateScore()
        ]);
    }

    public function historicScoreMessage(): string
    {
        return __('guess-the-number.historic-score', [
            'score' => $this->gameService->totalScore()
        ]);
    }

}
