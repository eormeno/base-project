<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\States\Tile\Hidden;
use App\FSM\IStateModel;

class Tile implements JsonSerializable, IStateModel
{
    private int $x;
    private int $y;

    private function __construct(
        private int $id,
        private Map $map,
        private bool $hasTrap = false,
        private bool $hasFlag = false,
        private bool $isMarkedAsClue = false,
        private int $trapsAround = 0,
        private ?string $state = null
    ) {
        $this->x = $id % $map->getWidth();
        $this->y = intdiv($id, $map->getWidth());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return 'tile' . $this->id;
    }

    public function getMap(): Map
    {
        return $this->map;
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

    public function isMarkedAsClue(): bool
    {
        return $this->isMarkedAsClue;
    }

    public function setMarkedAsClue(bool $isMarkedAsClue): void
    {
        $this->isMarkedAsClue = $isMarkedAsClue;
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function isRevealed(): bool
    {
        return $this->state === 'revealed';
    }

    public function setState(string|null $state): void
    {
        $this->state = $state;
    }

    public function updateState(string|null $state): void
    {
        $this->state = $state;
    }

    public static function fromJson(Map $map, array $data): Tile
    {
        $id = $data['id'];
        $state = $data['state'];
        $hasTrap = $data['trap'] ?? false;
        $hasFlag = $data['flag'] ?? false;
        $markedAsClue = $data['markedAsClue'] ?? false;
        $trapsAround = $data['trapsAround'] ?? 0;
        return new Tile($id, $map, $hasTrap, $hasFlag, $markedAsClue, $trapsAround, $state);
    }

    public static function newEmptyTile(Map $map, int $x, int $y): Tile
    {
        return self::fromJson($map, [
            'id' => $y * $map->getWidth() + $x,
            'trap' => false,
            'flag' => false,
            'markedAsClue' => false,
            'trapsAround' => 0,
            'state' => 'hidden'
        ]);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'trap' => $this->hasTrap,
            'flag' => $this->hasFlag,
            'markedAsClue' => $this->isMarkedAsClue,
            'trapsAround' => $this->trapsAround,
            'state' => $this->state,
        ];
    }

}
