<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;

class Map implements JsonSerializable
{
    private array $tiles;
    private int $width;
    private int $height;

    public static function fromJson(string $json): Map
    {
        $data = json_decode($json, true);
        $map = new Map($data['width'], $data['height']);
        $map->tiles = array_map(fn($tile) => new Tile($tile), $data['tiles']);
        return $map;
    }

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    private function getTileAt(int $x, int $y): Tile
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
