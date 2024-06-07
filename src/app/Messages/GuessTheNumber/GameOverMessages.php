<?php

namespace App\Messages\GuessTheNumber;

use App\Services\GuessTheNumber\AbstractComponent;

class GameOverMessages extends AbstractComponent
{

    public function gameOverMessage()
    {
        return __('guess-the-number.game-over', [
            'user_name' => $this->userRepository->name()
        ]);
    }

    public function gameOverSubtitle()
    {
        $game = $this->gameRepository->getGame();
        return __('guess-the-number.game-over-subtitle', [
            'random_number' => $game->random_number
        ]);
    }

}
