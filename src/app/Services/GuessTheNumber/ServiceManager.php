<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceManager;
use App\Repositories\Globals\UserRepository;
use App\Repositories\GuessTheNumber\GameRepository;

class ServiceManager extends AbstractServiceManager
{
    public function __construct()
    {
        $this->addService('gameConfigService', new GameConfigService($this));
        $this->addService('messageService', new MessageService($this));
        $this->addService('gameService', new GameService($this));
        $this->addService('guessService', new GuessService($this));
        $this->addService('gameRepository', new GameRepository($this));
        $this->addService('userRepository', new UserRepository($this));
    }
}
