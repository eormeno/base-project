<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;

class Map implements JsonSerializable
{
    private array $tiles;
    private int $width;
    private int $height;

    public static function fromJson(string|null $json, int $width = 8, int $height = 8): Map
    {
        if ($json === null) {
            $map = new Map($width, $height);
            $map->tiles = self::generateTiles($width, $height);
            return $map;
        }
        $data = json_decode($json, true);
        $width = $data['width'];
        $height = $data['height'];
        $tils = $data['tiles'];
        return new Map($width, $height, $tils);
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

    public function __construct(int $width, int $height, array $tilesAsArray = [])
    {
        $this->width = $width;
        $this->height = $height;
        $this->tiles = [];
        foreach ($tilesAsArray as $tileAsArray) {
            $this->tiles[] = new Tile($tileAsArray);
        }
    }

    private function getTileAt(int $x, int $y): Tile
    {
        return $this->tiles[$y * $this->width + $x];
    }

    private static function generateTiles(int $width, int $height): array
    {
        $tiles = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $tile = new Tile(['id' => $x + $y * $width]);
                $tiles[] = $tile->jsonSerialize();
            }
        }
        return $tiles;
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
