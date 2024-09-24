<?php
namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\CaseConverters;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function getAlias(): string
    {
        $shortName = (new ReflectionClass($this))->getShortName();
        return "{$shortName}_{$this->id}";
    }

    public function initialState(): ReflectionClass
    {
        return $this->states()[0]::StateClass();
    }

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

    public function updateState(ReflectionClass|null $rfl_state): void
    {
        if ($rfl_state) {
            $rfl_state = CaseConverters::pascalToKebab($rfl_state->getShortName());
        }

        $this->update(['state' => $rfl_state]);
    }

    public function enteredAt(): Attribute | null
    {
        return Attribute::make(
            get: fn (string | null $value) => $value ? Carbon::parse($value) : null,
            set: fn (Carbon|string|null $value) => $value ? $value->toDateTimeString() : null
        );
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
