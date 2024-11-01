<?php

namespace App\Goblets\MTQ\Components;

use App\Goblets\Base\Component;
use App\Goblets\MTQ\Prefabs\Tile;

class MapConfig extends Component {
    public int $width = 8;
    public int $height = 8;
    public int $traps = 8;
    public array $tiles = [];

    public function __construct(int $width = 8, int $height = 8, int $traps = 8)
    {
        $this->width = $width;
        $this->height = $height;
        $this->traps = $traps;
        $this->generateTiles();
    }

    public function generateTiles()
    {
        for ($i = 0; $i < $this->width; $i++) {
            for ($j = 0; $j < $this->height; $j++) {
                $this->tiles[] = new Tile();
            }
        }
    }
}
