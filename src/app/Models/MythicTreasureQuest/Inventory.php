<?php

namespace App\Models\MythicTreasureQuest;

use Illuminate\Support\Carbon;
use ReflectionClass;
use JsonSerializable;
use App\FSM\IStateModel;
use App\States\Inventory\InventoryDisplaying;

class Inventory implements JsonSerializable, IStateModel
{
    private array $items = [];
    private ?Item $selected = null;
    private ?string $state = null;
    private Carbon|null $started_at = null;

    public function __construct(?string $state = null, Carbon|null $started_at = null)
    {
        $this->state = $state;
        $this->started_at = $started_at;
    }

    public static function fromJson(array $data): Inventory
    {
        $items = $data['items'];
        $state = $data['state'];
        $started_at = $data['started_at'];
        $inventory = new Inventory($state, $started_at);

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

    public function getItemByTypeId(int $itemId): Item | null
    {
        foreach ($this->items as $item) {
            if ($item->getItemId() === $itemId) {
                return $item;
            }
        }
        return null;
    }

    public function jsonSerialize(): array
    {
        return [
            'state' => $this->state,
            'started_at' => $this->started_at,
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

    public function getEnteredAt(): string|null
    {
        return $this->started_at;
    }

    public function setEnteredAt(Carbon|string|null $startedAt): void
    {
        $this->started_at = $startedAt;
    }
    #endregion
}
