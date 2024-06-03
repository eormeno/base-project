<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceManager;
use App\Repositories\GuessTheNumber\GameRepository;

class ServiceManager extends AbstractServiceManager
{
    public function __construct()
    {
        $this->services = [
            'configService' => new GameConfigService($this),
            'messageService' => new MessageService($this),
            'gameService' => new GameService($this),
            'guessService' => new GuessService($this),
            'gameRepository' => new GameRepository($this),
        ];
    }
}
