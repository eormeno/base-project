<?php

use App\Models\Game;
use App\Models\GameObject\GameObject;

function existsGameObjectsInDatabase(array $rows): void
{
    $table = 'game_objects';
    $columns = ['id', 'name', 'game_id', 'game_object_id', 'state_component_id'];
    foreach ($rows as $id => $row) {
        test()->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    }
}

function rootGameObjectForGameIsCreated(Game $game) : GameObject
{
    $rootGameObject = GameObject::where('game_id', $game->id)->where('game_object_id', null)->first();
    test()->assertNotNull($rootGameObject);
    return $rootGameObject;
}

function gameObjectHasComponents(GameObject $gameObject, array $componentsTypes): void
{
    $components = $gameObject->components()->get();
    test()->assertEquals(count($components), count($componentsTypes));
    foreach ($components as $component) {
        $component->type = substr(strrchr($component->type, "\\"), 1);
        test()->assertTrue(in_array($component->type, $componentsTypes), "Component type {$component->type} is not in the list of expected components");
    }
}
