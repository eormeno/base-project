<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MtqTile;
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
        return $this->mapService->getMap()->getTileById($id);
    }

    public function changeState(int $id, string $state): void
    {
        $tile = $this->getTileById($id);
        $tile->setState($state);
        $this->gameRepository->saveMap();
    }

    public function markTileWithClue(MtqTile $tile): void
    {
        $tile->marked_as_clue = true;
        $tile->save();
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
