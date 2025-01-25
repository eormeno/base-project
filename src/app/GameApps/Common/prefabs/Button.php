<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab\Prefab;
use App\Models\GameObject\GameObject;

class Button extends Prefab
{
	public static function components(): array
	{
		return [
			'button' => []
		];
	}

	public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
	{
		$label_component = $gameObject->getComponent('button');
		$label_component->update($attributes);
	}
}
