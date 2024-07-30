<?php

// Path: src/app/Models/AStateModel.php

namespace App\Models;

use App\Traits\DebugHelper;
use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class AStateModel extends Model implements IStateModel
{
    use DebugHelper;

    protected $casts = [
        'state_children' => 'array',
        'state_attributes' => 'array',
    ];

    private static array $aliases = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $shortName = (new ReflectionClass($this))->getShortName();
        if (!isset(self::$aliases[$shortName])) {
            self::$aliases[$shortName] = new ReflectionClass($this);
        }
        //$this->log((new ReflectionClass($this))->getShortName());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        $shortName = (new ReflectionClass($this))->getShortName();
        return "{$shortName}_{$this->id}";
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

    public static function modelOf(string $alias): IStateModel
    {
        $shortName = substr($alias, 0, strpos($alias, '_'));
        $aliasId = substr($alias, strpos($alias, '_') + 1);
        $rflClass = self::$aliases[$shortName];
        $model = $rflClass->newInstance();
        // invoke the find static method of the model
        return $model->find($aliasId);
    }
}
