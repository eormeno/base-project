<?php

namespace App\Http\Controllers\GuessTheNumber\Repositories;

use App\Http\Controllers\GuessTheNumber\Services\GameConfigService;

class GuessTheNumberGameRepository
{
    private const GAME_SESSION_KEY = 'guess_the_number_game';

    public function __construct(
        protected GameConfigService $gameConfigService
    ) {
    }

    public function createNewGame(): array
    {
        $new_game = [
            'state' => 'initial',
            'min_number' => $this->gameConfigService->getMinNumber(),
            'max_number' => $this->gameConfigService->getMaxNumber(),
            'max_attemts' => $this->gameConfigService->getMaxAttempts(),
            'half_attempts' => $this->gameConfigService->getHalfAttempts(),
            'remaining_attempts' => $this->gameConfigService->getMaxAttempts(),
            'random_number' => 0,
            'score' => 0,
            'finished' => false,
        ];
        session()->put(self::GAME_SESSION_KEY, $new_game);
        return $new_game;
    }

    public function existsGame(): bool
    {
        return session()->has(self::GAME_SESSION_KEY);
    }

    public function getGame(): array
    {
        return session()->get(self::GAME_SESSION_KEY);
    }

    public function getRandomNumber(): int
    {
        return $this->getGame()['random_number'];
    }

    public function setRandomNumber($random_number): void
    {
        $game = $this->getGame();
        $game['random_number'] = $random_number;
        session()->put(self::GAME_SESSION_KEY, $game);
    }

    public function getRemainingAttempts(): int
    {
        return $this->getGame()['remaining_attempts'];
    }

    public function setRemainingAttempts($remaining_attempts): void
    {
        $game = $this->getGame();
        $game['remaining_attempts'] = $remaining_attempts;
        session()->put(self::GAME_SESSION_KEY, $game);
    }

    public function decreaseRemainingAttempts(): void
    {
        $remaining_attempts = $this->getRemainingAttempts();
        $remaining_attempts--;
        $this->setRemainingAttempts($remaining_attempts);
    }

    public function setGameState($state): void
    {
        $game = $this->getGame();
        $game['state'] = $state;
        session()->put(self::GAME_SESSION_KEY, $game);
    }

    public function getGameState(): string
    {
        return $this->getGame()['state'];
    }

    public function setGameSuccess(): void
    {
        $this->setGameState('success');
        $this->setGameFinished();
    }

    public function setGameOver(): void
    {
        $this->setGameState('game_over');
        $this->setGameFinished();
    }

    public function setGameFinished(): void
    {
        $game = $this->getGame();
        $game['finished'] = true;
        session()->put(self::GAME_SESSION_KEY, $game);
    }

    public function isGameFinished(): bool
    {
        return $this->getGame()['finished'];
    }

}
