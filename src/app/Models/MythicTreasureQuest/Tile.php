<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\FSM\IStateModel;
use App\States\Tile\Hidden;

class Tile implements JsonSerializable, IStateModel
{
    private function __construct(
        private int $id,
        private int $x,
        private int $y,
        private bool $hasTrap = false,
        private bool $hasFlag = false,
        private bool $isMarkedAsClue = false,
        private int $trapsAround = 0,
        private ?string $state = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return "tile{$this->id}";
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

    public static function fromJson(array $data): Tile
    {
        $id = $data['id'];
        $x = $data['x'];
        $y = $data['y'];
        $state = $data['state'];
        $hasTrap = $data['trap'] ?? false;
        $hasFlag = $data['flag'] ?? false;
        $markedAsClue = $data['markedAsClue'] ?? false;
        $trapsAround = $data['trapsAround'] ?? 0;
        return new Tile($id, $x, $y, $hasTrap, $hasFlag, $markedAsClue, $trapsAround, $state);
    }

    public static function newEmptyTile(int $id, int $x, int $y): Tile
    {
        return self::fromJson([
            'id' => $id,
            'x' => $x,
            'y' => $y,
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
            'x' => $this->x,
            'y' => $this->y,
            'trap' => $this->hasTrap,
            'flag' => $this->hasFlag,
            'markedAsClue' => $this->isMarkedAsClue,
            'trapsAround' => $this->trapsAround,
            'state' => $this->state,
        ];
    }

}
