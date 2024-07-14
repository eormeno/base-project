<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;
use App\FSM\IStateModel;
use Illuminate\Database\Eloquent\Model;

class Map implements JsonSerializable
{
    private array $tiles;
    private Model $castModel;

    private function __construct(
        private int $width,
        private int $height,
        private IStateModel | null $model = null,
        private string | null $fieldName = null
    ) {
    }

    public static function fromField(IStateModel $model, string $fieldName): Map
    {
        $data = $model->$fieldName;
        $width = $data['width'];
        $height = $data['height'];
        $tiles = $data['tiles'];
        $map = new Map($width, $height, $model, $fieldName);
        foreach ($tiles as $tile) {
            $map->addTile(Tile::fromJson($tile));
        }
        return $map;
    }

    public function save(): void
    {
        if ($this->model === null || $this->fieldName === null) {
            return;
        }
        $data = $this->jsonSerialize();
        $this->model->{$this->fieldName} = $data;
        $this->castModel = $this->model;
        $this->castModel->save();
    }

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
