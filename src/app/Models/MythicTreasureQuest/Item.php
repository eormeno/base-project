<?php

namespace App\Models\MythicTreasureQuest;

use ReflectionClass;
use JsonSerializable;
use App\FSM\IStateManagedModel;
use App\States\Item\ItemNormalState;

class Item implements JsonSerializable, IStateManagedModel
{
    private int $id;
    private int $item_id;
    private int $quantity;
    private ?string $state = null;

    public function __construct(int $id, int $item_id, int $quantity, ?string $state = null)
    {
        $this->id = $id;
        $this->item_id = $item_id;
        $this->quantity = $quantity;
        $this->state = $state;
    }

    public function getItemId(): int
    {
        return $this->item_id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function decrementQuantity(): void
    {
        $this->quantity--;
    }

    public static function fromJson(array $data): Item
    {
        $id = $data['id'];
        $item_id = $data['item_id'];
        $quantity = $data['quantity'];
        return new Item($id, $item_id, $quantity);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'state' => $this->state
        ];
    }

    #region IStateManagedModel
    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return 'item' . $this->id;
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return ItemNormalState::StateClass();
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->state = $state;
    }
    #endregion
}
