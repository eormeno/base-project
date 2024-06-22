<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;

class Map implements JsonSerializable
{
    private array $tiles;

    public static function fromJson(array $data): Map
    {
        $width = $data['width'];
        $height = $data['height'];
        $tiles = $data['tiles'];
        $map = new Map($width, $height);
        foreach ($tiles as $tile) {
            $map->addTile(Tile::fromJson($tile));
        }
        return $map;
    }

    public function __construct(
        private int $width,
        private int $height
    ) {
    }

    public function addTile(Tile $tile): void
    {
        $this->tiles[] = $tile;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getTiles(): array
    {
        return $this->tiles;
    }

    public function getTile(int $x, int $y): Tile
    {
        return $this->tiles[$y * $this->width + $x];
    }

    public function jsonSerialize(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'tiles' => $this->tiles
        ];
    }
}
