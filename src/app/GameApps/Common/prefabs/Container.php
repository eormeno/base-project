<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab\Prefab;
use App\Models\GameObject\GameObject;

class Container extends Prefab
{
	public static function structure(): array
	{
		return [
			'components' => self::components(),
		];
	}

	public static function components(): array
	{
		return [
			'container' => []
		];
	}

	public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
	{
		echo "Container::afterInstantiate " . $gameObject . "\n";
		$containerComponent = $gameObject->getComponent('container');
		// $children = [];
		// foreach ($gameObject->children as $child) {
		// 	$children[] = $child->id;
		// }
		$containerComponent->update($attributes);
		$containerComponent->update(['children' => $gameObject->children->pluck('id')]);
	}
}
