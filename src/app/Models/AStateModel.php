<?php

// Path: src/app/Models/AStateModel.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class AStateModel extends Model implements IStateModel
{

    protected $casts = [
        'state_children' => 'array',
        'state_attributes' => 'array',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        // get the short name of the class
        $shortName = (new ReflectionClass($this))->getShortName();
        return  "{$shortName}{$this->id}";
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->update(['state' => $state]);
    }

    public function getEnteredAt(): string|null
    {
        return $this->entered_at;
    }

    public function setEnteredAt(Carbon|string|null $enteredAt): void
    {
        $this->update(['entered_at' => $enteredAt]);
    }
}
