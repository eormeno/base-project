<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;
use App\Services\GuessTheNumber\MessageComponents\PlayingMessages;
use App\Services\GuessTheNumber\MessageComponents\SuccessMessages;
use App\Services\GuessTheNumber\MessageComponents\GameOverMessages;
use App\Services\GuessTheNumber\MessageComponents\ShowingClueMessages;
use App\Services\GuessTheNumber\MessageComponents\AskingToPlayMessages;

class MessageService extends AbstractServiceComponent
{
    private $messageComponents = [];

    public function __construct(
        AbstractServiceManager $serviceManager
    ) {
        $this->messageComponents = [
            new AskingToPlayMessages($serviceManager),
            new PlayingMessages($serviceManager),
            new SuccessMessages($serviceManager),
            new GameOverMessages($serviceManager),
            new ShowingClueMessages($serviceManager),
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
