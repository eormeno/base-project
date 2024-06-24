<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\States\Tile\Hidden;
use App\FSM\IStateManagedModel;

class Tile implements JsonSerializable, IStateManagedModel
{
    private int $x;
    private int $y;

    private function __construct(
        private int $id,
        private int $mapWidth,
        private bool $hasTrap = false,
        private bool $hasFlag = false,
        private int $trapsAround = 0,
        private ?string $state = null
    ) {
        $this->x = $id % $mapWidth;
        $this->y = intdiv($id, $mapWidth);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Hidden::StateClass();
    }

    public function getHasTrap(): bool
    {
        return $this->hasTrap;
    }

    public function setHasTrap(bool $hasTrap): void
    {
        $this->hasTrap = $hasTrap;
    }

    public function getHasFlag(): bool
    {
        return $this->hasFlag;
    }

    public function setHasFlag(bool $hasFlag): void
    {
        $this->hasFlag = $hasFlag;
    }

    public function getTrapsAround(): int
    {
        return $this->trapsAround;
    }

    public function setTrapsAround(int $trapsAround): void
    {
        $this->trapsAround = $trapsAround;
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->state = $state;
    }

    public static function fromJson(array $data): Tile
    {
        $id = $data['id'];
        $mapWidth = $data['mapWidth'];
        $state = $data['state'];
        $hasTrap = $data['trap'] ?? false;
        $hasFlag = $data['flag'] ?? false;
        $trapsAround = $data['trapsAround'] ?? 0;
        return new Tile($id, $mapWidth, $hasTrap, $hasFlag, $trapsAround, $state);
    }

    public static function newEmptyTile(int $mapWidth, int $x, int $y): Tile
    {
        return self::fromJson([
            'id' => $y * $mapWidth + $x,
            'mapWidth' => $mapWidth,
            'trap' => false,
            'flag' => false,
            'trapsAround' => 0,
            'state' => 'hidden'
        ]);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'mapWidth' => $this->mapWidth,
            'trap' => $this->hasTrap,
            'flag' => $this->hasFlag,
            'trapsAround' => $this->trapsAround,
            'state' => $this->state,
        ];
    }

}
