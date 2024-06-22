<?php

namespace Database\Factories;

use App\Models\MythicTreasureQuest\Map;
use App\Models\MythicTreasureQuest\Tile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MythicTreasureQuestGame>
 */
class MythicTreasureQuestGameFactory extends Factory
{
    public function definition(): array
    {
        $map = $this->generateMap();

        return [
            'level' => 1,
            'map' => $map->jsonSerialize(),
            'health' => 100,
            'is_finished' => false
        ];
    }

    private function generateMap(): Map
    {
        $width = 8;
        $height = 8;
        $map = new Map($width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $jsonTile = [
                    'id' => $y * $width + $x,
                    'trap' => false,
                    'flag' => false,
                    'trapsAround' => 0,
                    'state' => null
                ];
                $map->addTile(Tile::fromJson($jsonTile));
            }
        }
        $this->fillTraps(5, $map);
        return $map;
    }

    private function fillTraps(int $count, Map $map): void
    {
        $width = $map->getWidth();
        $height = $map->getHeight();
        $randomPositions = $this->generateUniqueRandomPositions($count, $width, $height);
        foreach ($randomPositions as $pos) {
            $x = $pos['x'];
            $y = $pos['y'];
            $tile = $map->getTile($x, $y);
            $tile->hasTrap = true;
        }
    }

    private function isPositionInArray(int $x, int $y, array $positions): bool
    {
        foreach ($positions as $pos) {
            if ($pos['x'] === $x && $pos['y'] === $y) {
                return true;
            }
        }
        return false;
    }

    private function generateUniqueRandomPositions(int $count, int $width, int $height): array
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
