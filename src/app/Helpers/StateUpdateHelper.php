<?php

namespace App\Helpers;

use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\CaseConverters;
use Illuminate\Support\Carbon;
use App\Services\AbstractServiceManager;

class StateUpdateHelper
{
    protected IStateModel $model;
    protected AbstractServiceManager $serviceManager;

    public function __construct(
        AbstractServiceManager $serviceManager,
        IStateModel $model
    ) {
        $this->model = $model;
        $this->serviceManager = $serviceManager;
    }

    // public function getInitialStateClass(): ReflectionClass
    // {
    //     //return $this->model->states()[0]::StateClass();
    //     return $this->model->initialState();
    // }

    // public function readState(): ReflectionClass|null
    // {
    //     // $kebab_state_name = $this->model->_getState();
    //     // $rfl_class = $this->stateNameToClass($kebab_state_name);
    //     // return $rfl_class;
    //     return $this->model->currentState();
    // }

    // public function saveState(ReflectionClass|null $rfl_state): void
    // {
    //     // TODO: modify here because when the state is the same as the previous state, the event should not be triggered.
    //     // $oldState = $this->readState();
    //     $newState = $rfl_state;
    //     if ($rfl_state) {
    //         $rfl_state = CaseConverters::pascalToKebab($rfl_state->getShortName());
    //     }
    //     $this->model->updateState($rfl_state);
    // }

    // public function getEnteredAt(): Carbon|null
    // {
    //     $enteredAt = $this->model->getEnteredAt();
    //     return $enteredAt ? Carbon::parse($enteredAt) : null;
    // }

    // public function setEnteredAt(Carbon|string|null $enteredAt): void
    // {
    //     $this->model->setEnteredAt($enteredAt);
    // }

    // TODO: Este código está duplicado en AStateModel
    // private function stateNameToClass(string|null $dashed_state_name): ReflectionClass
    // {
    //     if (!$dashed_state_name) {
    //         return $this->getInitialStateClass();
    //     }
    //     return $this->findClassNameInClassesArray($dashed_state_name);
    // }

    // // TODO: Se podría evitar la búsqueda si utilizara un índice entero en lugar de una cadena.
    // private function findClassNameInClassesArray(string $dashed_state_name) : ReflectionClass
    // {
    //     $short_class_name = CaseConverters::kebabToPascal($dashed_state_name);
    //     $classes = $this->model->states();
    //     foreach ($classes as $class) {
    //         if ($class::StateClass()->getShortName() === $short_class_name) {
    //             return $class::StateClass();
    //         }
    //     }
    //     throw new \Exception("Class $short_class_name not found in the array of classes.");
    // }
}
