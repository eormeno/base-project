<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\States\Tile\Initial;
use App\FSM\IStateManagedModel;

class Tile implements JsonSerializable, IStateManagedModel {
    private int $id;
    private string|null $state = null;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->state = $data['state'] ?? null;
    }

    public function getId(): int {
        return $this->id;
    }

    public static function getInitialStateClass(): ReflectionClass {
        return Initial::StateClass();
    }

    public function getState(): string|null {
        return $this->state;
    }

    public function updateState(string|null $state): void {
        $this->state = $state;
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'state' => $this->state
        ];
    }

}
