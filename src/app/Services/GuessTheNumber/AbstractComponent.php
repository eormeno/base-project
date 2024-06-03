<?php

namespace App\Services\GuessTheNumber;

use App\Repositories\Globals\UserRepository;
use App\Repositories\GuessTheNumber\GameRepository;

abstract class AbstractComponent
{
    public function __construct(
        protected UserRepository $userRepository,
        protected GameRepository $gameRepository,
        protected GameConfigService $gameConfigService,
    ) {
    }
}
