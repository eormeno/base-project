<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Http\Controllers\StateContextController;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends StateContextController
{
    public function __construct(GuessTheNumberGameServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->stateStorage = $serviceManager->gameStateStorageService;
    }
}
