<?php

namespace App\Helpers;

use ReflectionClass;
use App\Utils\CaseConverters;
use Illuminate\Database\Eloquent\Model;

class StateUpdateHelper
{
    protected Model $object;
    protected string $strIdFieldName;
    protected string $strStateFieldName;
    protected $initialClass;

    public function __construct(Model $object, array $stateConfig)
    {
        $this->object = $object;
        $this->stateConfig = $stateConfig;
        $this->strIdFieldName = $stateConfig['id_field'];
        $this->strStateFieldName = $stateConfig['state_field'];
        $this->initialClass = $stateConfig['initial'];
    }

    public function getInitialStateClass(): ReflectionClass
    {
        return $this->initialClass::StateClass();
    }

    public function readState(): ReflectionClass|null
    {
        $strField = $this->strStateFieldName;
        $kebab_state_name = $this->object->$strField;
        $rfl_class = $this->stateNameToClass($kebab_state_name);
        return $rfl_class;
    }

    public function saveState(ReflectionClass|null $rfl_state): void
    {
        if ($rfl_state) {
            $rfl_state = CaseConverters::pascalToKebab($rfl_state->getShortName());
        }
        $strField = $this->strStateFieldName;
        $this->object->$strField = $rfl_state;
        $this->object->save();
    }

    private function stateNameToClass(string|null $dashed_state_name): ReflectionClass
    {
        if (!$dashed_state_name) {
            return $this->getInitialStateClass();
        }
        // todo: refactor this to a more generic method. because this class construction assumes
        // that the state class is in the same namespace as the initial state class.
        $namespace = $this->getInitialStateClass()->getNamespaceName();
        $short_class_name = CaseConverters::kebabToPascal($dashed_state_name);
        $full_class_name = $namespace . '\\' . $short_class_name;
        return $full_class_name::StateClass();
    }
}
