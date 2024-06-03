<?php

namespace App\Services\GuessTheNumber;

use App\Repositories\Globals\UserRepository;
use App\Repositories\GuessTheNumber\GameRepository;
use App\Services\GuessTheNumber\MessageComponents\PlayingMessages;
use App\Services\GuessTheNumber\MessageComponents\SuccessMessages;
use App\Services\GuessTheNumber\MessageComponents\GameOverMessages;
use App\Services\GuessTheNumber\MessageComponents\AskingToPlayMessages;

class MessageService
{
    private $messageComponents = [];

    public function __construct(
        protected UserRepository $userRepository,
        protected GameRepository $gameRepository,
        protected GameConfigService $gameConfigService
    ) {
        $this->messageComponents = [
            new AskingToPlayMessages($userRepository, $gameRepository, $gameConfigService),
            new PlayingMessages($userRepository, $gameRepository, $gameConfigService),
            new SuccessMessages($userRepository, $gameRepository, $gameConfigService),
            new GameOverMessages($userRepository, $gameRepository, $gameConfigService),
        ];
    }

    public function __call($name, $arguments)
    {
        foreach ($this->messageComponents as $messageComponent) {
            if (method_exists($messageComponent, $name)) {
                return $messageComponent->$name(...$arguments);
            }
        }
    }

}
