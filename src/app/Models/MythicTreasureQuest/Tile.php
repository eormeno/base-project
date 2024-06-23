<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\States\Tile\Hidden;
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

    public function __get($name): mixed
    {
        switch ($name) {
            case 'hasFlag':
                return $this->hasFlag;
            case 'trapsAround':
                return $this->trapsAround;
            case 'hasTrap':
                return $this->hasTrap;
            case 'state':
                return $this->state;
            default:
                throw new \Exception("Property $name not found");
        }
    }

    public function __set($name, $value): void
    {
        switch ($name) {
            case 'hasFlag':
                $this->hasFlag = $value;
                break;
            case 'trapsAround':
                $this->trapsAround = $value;
                break;
            case 'hasTrap':
                $this->hasTrap = $value;
                break;
            case 'state':
                $this->state = $value;
                break;
            default:
                throw new \Exception("Property $name not found");
        }
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Hidden::StateClass();
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
            'flag' => $this->hasFlag,
            'trapsAround' => $this->trapsAround,
            'state' => $this->state,
        ];
    }

}
