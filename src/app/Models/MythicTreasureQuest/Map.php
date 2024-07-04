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
            $map->addTile(Tile::fromJson($map, $tile));
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

    public function getTileById(int $id): Tile
    {
        return $this->tiles[$id];
    }

    public function getTile(int $x, int $y): Tile
    {
        return $this->tiles[$y * $this->width + $x];
    }

    public function replaceTile(Tile $tile): void
    {
        $this->tiles[$tile->getId()] = $tile;
    }

    public function isValid(int $x, int $y): bool
    {
        return $x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height;
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
