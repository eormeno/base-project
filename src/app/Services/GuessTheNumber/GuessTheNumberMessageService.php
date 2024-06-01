<?php

namespace App\Services\GuessTheNumber;

use App\Repositories\Globals\UserRepository;
use App\Repositories\GuessTheNumber\GameRepository;

class GuessTheNumberMessageService
{

    public function __construct(
        protected UserRepository $userRepository,
        protected GameRepository $gameRepository,
        protected GameConfigService $gameConfigService,
    ) {
    }

    public function welcomeMessage()
    {
        return __('guess-the-number.description', [
            'user_name' => $this->userRepository->name(),
            'remaining_attemts' => $this->gameConfigService->getMaxAttempts(),
            'min_number' => $this->gameConfigService->getMinNumber(),
            'max_number' => $this->gameConfigService->getMaxNumber()
        ]);
    }

    public function successMessage(): string
    {
        return __('guess-the-number.success', [
            'user_name' => $this->userRepository->name()
        ]);
    }

    public function successSubtitleMessage(): string
    {
        return __('guess-the-number.success-subtitle', [
            'attempts' => $this->gameConfigService->getMaxAttempts() - $this->gameRepository->getRemainingAttempts()
        ]);
    }

    public function gameOverMessage()
    {
        return __('guess-the-number.game-over', [
            'user_name' => $this->userRepository->name()
        ]);
    }

    public function gameOverSubtitle()
    {
        return __('guess-the-number.game-over-subtitle', [
            'random_number' => $this->gameRepository->getRandomNumber()
        ]);
    }

    public function greaterMessage($number)
    {
        return __('guess-the-number.greater', [
            'number' => $number
        ]);
    }

    public function lowerMessage($number)
    {
        return __('guess-the-number.lower', [
            'number' => $number
        ]);
    }

    public function cheatMessage()
    {
        return __('guess-the-number.cheat', [
            'random_number' => $this->gameRepository->getRandomNumber()
        ]);
    }

    public function invalidNumberMessage()
    {
        return __('guess-the-number.invalid_number', [
            'min_number' => $this->gameConfigService->getMinNumber(),
            'max_number' => $this->gameConfigService->getMaxNumber()
        ]);
    }

    public function remainingAttemptsMessage()
    {
        $remaining_attempts = $this->gameRepository->getRemainingAttempts();

        if ($remaining_attempts == 1) {
            return __('guess-the-number.last_attempt');
        }
        if ($remaining_attempts == $this->gameConfigService->getMaxAttempts()) {
            return __('guess-the-number.starting_attempts', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        if ($remaining_attempts <= $this->gameConfigService->getHalfAttempts()) {
            return __('guess-the-number.remaining_half', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        return __('guess-the-number.remaining', [
            'remaining_attemts' => $remaining_attempts
        ]);
    }
}
