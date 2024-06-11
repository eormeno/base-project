<?php

namespace App\Services;

use ReflectionClass;
use App\Models\GuessTheNumberGame;
use App\States\GuessTheNumber\Initial;
use Illuminate\Database\Eloquent\Model;

class StateManager
{
    protected $statesMap = [
        GuessTheNumberGame::class => [
            'initial' => Initial::class,
            'state_field' => 'state',
            'id_field' => 'id'
        ],
    ];

    public function getState(Model $model)
    {
        $class_name = get_class($model);
        if (!isset($this->statesMap[$class_name])) {
            throw new \Exception("State for $class_name is not defined.");
        }
        $short_class_name = (new ReflectionClass($model))->getShortName();
        $id_field = $this->statesMap[$class_name]['id_field'];
        $state_field = $this->statesMap[$class_name]['state_field'];
        $id = $model->$id_field;
        $state_value = $model->$state_field;
        echo "<b>$short_class_name ($id)</b>: $state_value" . PHP_EOL;
    }


}
