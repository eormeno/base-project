<?php

namespace App\Models\Components\GTN\Messages;

use App\Contracts\IMessageProvider;

class InitialStateViewMessages implements IMessageProvider
{
    public function getMessages(array $parameters): array
    {
        return [
            'description' => $this->descriptionMessage(),
            'yes_button' => $this->yesButton(),
            'ranking_title' => $this->rankingTitle(),
            'ranking' => [
                ['name' => 'Jugador 1', 'score' => 100],
                ['name' => 'Jugador 2', 'score' => 90],
                ['name' => 'Jugador 3', 'score' => 80],
                ['name' => 'Jugador 4', 'score' => 70],
                ['name' => 'Jugador 5', 'score' => 60],
            ],
        ];
    }

    private function descriptionMessage()
    {
        return __('guess-the-number.description', [
            'user_name' => 'auth()->user()->name', //$this->userRepository->name(),
            'remaining_attemts' => 10, //$this->gameConfigService->getMaxAttempts(),
            'min_number' => 1, //$this->gameConfigService->getMinNumber(),
            'max_number' => 1024, //$this->gameConfigService->getMaxNumber()
        ]);
    }

    private function yesButton()
    {
        return __('guess-the-number.want-to-play');
    }

    private function rankingTitle()
    {
        return __('guess-the-number.best-scores');
    }
}
