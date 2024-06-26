<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\FSM\IStateManagedModel;
use App\States\Inventory\InventoryDisplaying;

class Inventory implements JsonSerializable, IStateManagedModel
{
    private array $items = [];
    private ?Item $selected = null;
    private ?string $state = null;

    public function __construct(?string $state = null)
    {
        $this->state = $state;
    }

    public static function fromJson(array $data): Inventory
    {
        $items = $data['items'];
        $state = $data['state'];
        $inventory = new Inventory($state);

        foreach ($items as $item) {
            $inventory->addItem(Item::fromJson($item));
        }
        return $inventory;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem(int $index): Item
    {
        return $this->items[$index];
    }

    public function jsonSerialize(): array
    {
        return [
            'state' => $this->state,
            'items' => $this->items
        ];
    }

    #region IStateManagedModel
    public function getId(): int
    {
        return 0;
    }

    public function getAlias(): string
    {
        return 'inventory';
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return InventoryDisplaying::StateClass();
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function updateState(?string $state): void
    {
        $this->state = $state;
    }
    #endregion
}
