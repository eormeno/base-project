<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;
use App\Models\MythicTreasureQuestGame;
use App\States\MythicTreasureQuest\Initial;
use App\States\MythicTreasureQuest\Flagging;

class Playing extends StateAbstractImpl
{
    public int $width = 8;
    public int $height = 8;
    public array $list = [];
    public bool $playAgain = false;

    private function cast(): MythicTreasureQuestGame
    {
        return $this->model;
    }

    public function onRefresh(): void
    {
        $map = $this->context->gameRepository->getMap();
        $inventory = $this->context->inventoryRepository->getInventory();
        $this->context->stateManager->enqueueForRendering($inventory, $this->cast());
        $this->list = $this->context->stateManager->enqueueAllForRendering($map->getTiles(), $this->cast());
        $this->width = $map->getWidth();
        $this->height = $map->getHeight();
    }

    public function onPlayAgainEvent()
    {
        $this->playAgain = false;
        $this->context->gameRepository->restartGame();
        return Initial::StateClass();
    }

    public function onFlagEvent()
    {
        return Flagging::StateClass();
    }

    public function onGameOverEvent(): void
    {
        $this->errorToast('Game Over');
        $this->playAgain = true;
        $this->refresh();
    }

}
