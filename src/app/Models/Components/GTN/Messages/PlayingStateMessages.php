<?php

namespace App\Models\Components\GTN\Messages;

use App\Contracts\IMessageProvider;

class PlayingStateMessages implements IMessageProvider
{
    public function getMessages(array $parameters): array
    {
        return [
            'notification' => $this->remainingAttemptsMessage($parameters)
        ];
    }

    public function remainingAttemptsMessage(array $parameters): string
    {
        $remaining_attempts = $parameters['remaining_attempts'];

        if ($remaining_attempts == 1) {
            return __('guess-the-number.last_attempt');
        }
        if ($remaining_attempts == $parameters['max_attempts']) {
            return __('guess-the-number.starting_attempts', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        if ($remaining_attempts <= $parameters['max_attempts'] / 2) {
            return __('guess-the-number.remaining_half', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        return __('guess-the-number.remaining', [
            'remaining_attemts' => $remaining_attempts
        ]);
    }
}
