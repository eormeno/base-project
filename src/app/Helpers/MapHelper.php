<?php

namespace App\Helpers;

use App\Models\MythicTreasureQuest\Map;
use App\Models\MythicTreasureQuest\Tile;

class MapHelper
{

    public static function generateMap(int $width, int $height): Map
    {
        $map = Map::fromJson([
            "width" => $width,
            "height" => $height,
            "tiles" => []]);

        $tileId = 0;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $map->addTile(Tile::newEmptyTile($tileId++, $x, $y));
            }
        }
        self::fillTraps(8, $map);
        return $map;
    }

    private static function fillTraps(int $count, Map $map): void
    {
        $width = $map->getWidth();
        $height = $map->getHeight();
        $randomPositions = self::generateUniqueRandomPositions($count, $width, $height);
        foreach ($randomPositions as $pos) {
            $x = $pos['x'];
            $y = $pos['y'];
            $tile = $map->getTile($x, $y);
            $tile->setHasTrap(true);
            self::incrementTrapsAround($map, $x, $y);
        }
    }

    private static function incrementTrapsAround(Map $map, int $x, int $y): void
    {
        $width = $map->getWidth();
        $height = $map->getHeight();
        $directions = [[-1, -1], [0, -1], [1, -1], [-1, 0], [1, 0], [-1, 1], [0, 1], [1, 1]];
        foreach ($directions as $dir) {
            $newX = $x + $dir[0];
            $newY = $y + $dir[1];
            if ($newX >= 0 && $newX < $width && $newY >= 0 && $newY < $height) {
                $tile = $map->getTile($newX, $newY);
                $tile->setTrapsAround($tile->getTrapsAround() + 1);
            }
        }
    }

    private static function generateUniqueRandomPositions(int $count, int $width, int $height): array
    {
        $positions = [];
        while (count($positions) < $count) {
            $position = [
                'x' => rand(0, $width - 1),
                'y' => rand(0, $height - 1)
            ];
            if (!in_array($position, $positions)) {
                $positions[] = $position;
            }
        }
        return $positions;
    }
}
