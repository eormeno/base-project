<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab\Prefab;
use App\Models\GameObject\GameObject;

class Label extends Prefab
{
	public static function components(): array
	{
		return [
			'label' => []
		];
	}

	public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
	{
		$label_component = $gameObject->getComponent('label');
		$label_component->update($attributes);
	}
}
