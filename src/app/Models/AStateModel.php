<?php
namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\CaseConverters;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class AStateModel extends Model implements IStateModel
{
    protected $casts = ['children' => 'array'];
    private static array $aliases = [];
    private static array $states = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $shortName = (new ReflectionClass($this))->getShortName();
        if (!isset(self::$aliases[$shortName])) {
            self::$aliases[$shortName] = new ReflectionClass($this);
        }
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

    // public function _getState(): string|null
    // {
    //     return $this->state;
    // }

    public function initialState(): ReflectionClass
    {
        return $this->states()[0]::StateClass();
    }

    // TODO: Este método está duplicado en StateUpdateHelper
    public function currentState(): ReflectionClass
    {
        $dashed_state_name = $this->state;
        if (!$dashed_state_name) {
            return $this->initialState();
        }
        return $this->findClassNameInClassesArray($dashed_state_name);
    }

    // TODO: Se podría evitar la búsqueda si utilizara un índice entero en lugar de una cadena.
    private function findClassNameInClassesArray(string $dashed_state_name): ReflectionClass
    {
        $short_class_name = CaseConverters::kebabToPascal($dashed_state_name);
        $classes = $this->states();
        foreach ($classes as $class) {
            if ($class::StateClass()->getShortName() === $short_class_name) {
                return $class::StateClass();
            }
        }
        throw new \Exception("Class $short_class_name not found in the array of classes.");
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
        $rflClass = null;
        if (!isset(self::$aliases[$shortName])) {
            // TODO: acá el problema es asumir que todos los modelos están en el mismo namespace
            $rflClass = new ReflectionClass("App\\Models\\{$shortName}");
            self::$aliases[$shortName] = $rflClass;
        } else {
            $rflClass = self::$aliases[$shortName];
        }
        // $rflClass = self::$aliases[$shortName];

        if (!isset(self::$states[$alias])) {
            self::$states[$alias] = $rflClass->newInstance()->find($aliasId);
        }
        return self::$states[$alias];
    }
}
