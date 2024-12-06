<?php

namespace App\Models\Components\GTN\Messages;

use App\Contracts\IMessageProvider;

class ShowingClueStateMessages implements IMessageProvider
{
    public function getMessages(array $parameters): array
    {
        return [
            'title' => $this->titleMessage(),
            'good_luck' => $this->goodLuckMessage(),
            'clues' => $this->cluesMessage(),
            'yes_i_accept_the_challenge' => $this->yesIAcceptTheChallengeMessage(),
            'another_challenge' => $this->anotherChallengeMessage(),
        ];
    }

    private function titleMessage()
    {
        return __('guess-the-number.title');
    }

    private function goodLuckMessage()
    {
        return __('guess-the-number.good-luck');
    }

    private function cluesMessage()
    {
        return __('guess-the-number.clues');
    }

    private function yesIAcceptTheChallengeMessage()
    {
        return __('guess-the-number.yes-i-accept-the-challenge');
    }

    private function anotherChallengeMessage()
    {
        return __('guess-the-number.another-challenge');
    }
}
