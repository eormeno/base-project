<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\States\Tile\Initial;
use App\FSM\IStateManagedModel;

class Tile implements JsonSerializable, IStateManagedModel
{
    public function __construct(
        private int $id,
        private bool $hasTrap = false,
        private bool $hasFlag = false,
        private int $trapsAround = 0,
        private ?string $state = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function hasTrap(): bool
    {
        return $this->hasTrap;
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
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
        $state = $data['state'];
        $hasTrap = $data['trap'] ?? false;
        $hasFlag = $data['flag'] ?? false;
        $trapsAround = $data['trapsAround'] ?? 0;
        return new Tile($id, $hasTrap, $hasFlag, $trapsAround, $state);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'trap' => $this->hasTrap,
            'state' => $this->state,
        ];
    }

}
