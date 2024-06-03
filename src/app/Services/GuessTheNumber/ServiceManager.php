<?php

namespace App\Services\GuessTheNumber;

class ServiceManager
{
    private $services = [];

    public function __construct()
    {
        // $game = new GameService($this);

        // $this->services = [
        //     'game' => new GameService($this),
        //     'message' => new MessageService(),
        // ];
    }

    public function __get($name)
    {
        return $this->services[$name];
    }
}
