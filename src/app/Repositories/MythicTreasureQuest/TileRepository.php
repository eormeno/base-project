<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class TileRepository extends AbstractServiceComponent
{
    private GameRepository $gameRepository;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        parent::__construct($serviceManager);
        $this->gameRepository = $serviceManager->get('gameRepository');
    }

    public function markTileWithClue(Tile $tile): void
    {
        $tile->setMarkedAsClue(true);
        $this->gameRepository->saveMap();
        $this->requireRefresh($tile);
    }

    public function markTileWithFlag(Tile $tile): void
    {
        $tile->setHasFlag(true);
        $this->gameRepository->saveMap();
        $this->requireRefresh($tile);
    }

}
