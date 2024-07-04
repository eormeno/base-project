<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Traits\DebugHelper;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class TileRepository extends AbstractServiceComponent
{
    use DebugHelper;
    private GameRepository $gameRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->gameRepository = $serviceManager->get('gameRepository');
    }

    public function getTileById(int $id): Tile
    {
        return $this->gameRepository->getMap()->getTileById($id);
    }

    public function changeState(int $id, string $state): void
    {
        $tile = $this->getTileById($id);
        $tile->setState($state);
        $this->gameRepository->saveMap();
    }

    public function markTileWithClue(Tile $tile): void
    {
        $tile->setMarkedAsClue(true);
        $this->gameRepository->saveMap();
        $this->sendEvent($tile, 'clue_marked');
        $this->requireRefresh($tile);
    }

    public function markTileWithFlag(Tile $tile): void
    {
        $tile->setHasFlag(true);
        $this->gameRepository->saveMap();
        $this->requireRefresh($tile);
    }

}
