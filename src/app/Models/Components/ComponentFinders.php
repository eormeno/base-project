<?php

namespace App\Models\Components;

use App\Models\GameService;
use App\Utils\ReflectionUtils;

class ComponentFinders extends ComponentBase
{

    protected function findComponent(string $slug_type): Component
    {
        $type = ReflectionUtils::componentClass($slug_type);
        $game_object = $this->super->gameObject;
        return $game_object->components()->first([
            'type' => $type,
        ])->first()->subclass();
    }

	protected function validState(string $state): bool {
		return $this->super->gameObject->next_state_components->has($state);
	}

    public function getService(string $slug_type): GameService
    {
        return $this->super->gameObject->game->getService($slug_type);
    }
}
