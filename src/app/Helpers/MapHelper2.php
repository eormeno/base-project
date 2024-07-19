<?php

namespace App\Helpers;

class MapHelper2
{

    public static function generateMap(int $width, int $height, int $traps): array
    {
        $tiles = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $tiles[] = [
                    'x' => $x,
                    'y' => $y,
                    'state' => null,
                    'has_trap' => false,
                    'has_flag' => false,
                    'marked_as_clue' => false,
                    'traps_around' => 0,
                ];
            }
        }
        self::fillTraps($traps, $width, $height, $tiles);
        return $tiles;
    }

    private static function fillTraps(int $count, int $width, int $height, array &$map): void
    {
        $randomPositions = self::generateUniqueRandomPositions($count, $width * $height);
        foreach ($randomPositions as $pos) {
            $x = $map[$pos]['x'];
            $y = $map[$pos]['y'];
            $map[$pos]['has_trap'] = true;
            self::incrementTrapsAround($map, $width, $height, $x, $y);
        }
    }

    private static function incrementTrapsAround(array &$map, int $width, int $height, int $x, int $y): void
    {
        $directions = [[-1, -1], [0, -1], [1, -1], [-1, 0], [1, 0], [-1, 1], [0, 1], [1, 1]];
        foreach ($directions as $dir) {
            $newX = $x + $dir[0];
            $newY = $y + $dir[1];
            if ($newX >= 0 && $newX < $width && $newY >= 0 && $newY < $height) {
                $tile = $map[$newY * $width + $newX];
                $tile['traps_around']++;
            }
        }
    }

    private static function generateUniqueRandomPositions(int $count, int $mapSize): array
    {
        $positions = [];
        while (count($positions) < $count) {
            $position = rand(0, $mapSize - 1);
            if (!in_array($position, $positions)) {
                $positions[] = $position;
            }
        }
        return $positions;
    }
}
