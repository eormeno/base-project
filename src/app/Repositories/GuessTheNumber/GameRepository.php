<?php

namespace App\Repositories\GuessTheNumber;

use App\Models\GuessTheNumberGame;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent
{
    public function createEmptyNewGame(): void
    {
        GuessTheNumberGame::factory()->for(auth()->user())->create();
    }

    public function getGame(): GuessTheNumberGame
    {
        if (!auth()->user()->guessTheNumberGames()->exists()) {
            $this->createEmptyNewGame();
        }
        return auth()->user()->guessTheNumberGames;
    }

    public function updateRemainingAttempts(int $remainingAttempts): void
    {
        $game = $this->getGame();
        $game->update(['remaining_attempts' => $remainingAttempts]);
    }

    public function decreaseRemainingAttempts(): void
    {
        $game = $this->getGame();
        $game->update(['remaining_attempts' => $game->remaining_attempts - 1]);
    }

    public function getRanking(): array
    {
        $xxx = GuessTheNumberGame::select('user_id', 'score')
            ->orderBy('score', 'desc')
            ->with('user')
            ->where('score', '>', 0)
            ->limit(5)
            ->get()
            ->toArray();
        $ranking = [];
        foreach ($xxx as $item) {
            $ranking[$item['user']['name']] = $item['score'];
        }
        return $ranking;
    }
}
