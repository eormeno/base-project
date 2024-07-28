<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\FSM\IStateModel;
use App\Traits\DebugHelper;
use Illuminate\Support\Carbon;
use App\States\Map\MapDisplaying;
use Illuminate\Database\Eloquent\Model;

class Map implements JsonSerializable, IStateModel
{
    use DebugHelper;
    private array $tiles;
    private Model $castModel;

    private function __construct(
        private int $width,
        private int $height,
        private ?string $state = null,
        private IStateModel|null $model = null,
        private string|null $fieldName = null,
        private Carbon|null $enteredAt = null
    ) {
    }

    #region IStateManagedModel
    public function getId(): int
    {
        return 0;
    }

    public function getAlias(): string
    {
        return 'map';
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return MapDisplaying::StateClass();
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->state = $state;
    }

    public function getEnteredAt(): string|null
    {
        return $this->enteredAt;
    }

    public function setEnteredAt(Carbon|string|null $enteredAt): void
    {
        $this->entered_at = $enteredAt;
    }
    #endregion

    public static function fromField(IStateModel $model, string $fieldName): Map
    {
        $data = $model->$fieldName;
        return self::fromJson($data, null, $model, $fieldName);
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

    public static function fromJson(
        array $data,
        $parent = null,
        IStateModel|null $model = null,
        string|null $field = null
    ): Map {
        $width = $data['width'];
        $height = $data['height'];
        $state = $data['state'];
        $tiles = $data['tiles'];
        $entered_at = $data['entered_at'] ?? null;
        $map = new Map($width, $height, $state, $model, $field, $entered_at);
        foreach ($tiles as $tile) {
            $map->addTile(Tile::fromJson($tile, $map, $model, $field));
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
        // convert the tiles array to an associative array whose keys are the tile ids
        $tiles = [];
        foreach ($this->tiles as $tile) {
            $tiles[$tile->getId()] = $tile;
        }

        return [
            'width' => $this->width,
            'height' => $this->height,
            'state' => $this->state,
            'tiles' => $this->tiles,
            'entered_at' => $this->enteredAt
        ];
    }
}
