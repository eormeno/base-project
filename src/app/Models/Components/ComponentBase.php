<?php

namespace App\Models\Components;

use App\Models\Game;
use App\Models\Event;
use App\Traits\DebugHelper;
use App\Utils\ReflectionUtils;
use App\Models\GameObject\Base;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentBase extends Model
{
	use DebugHelper;
	public $timestamps = false;
	protected $fillable = ['type', 'game_object_id', 'enabled', 'state', 'messages'];
	protected $casts = [
		'enabled' => 'boolean',
		'messages' => 'array',
	];

	public function gameObject(): BelongsTo
	{
		// if current class is Component.
		if (get_class($this) === Component::class) {
			return $this->belongsTo(GameObject::class);
		}
		// if current class is subclass of Component.
		$super = $this->super;
		return $super->gameObject();
	}

	public function game(): Game
	{
		return $this->gameObject()->first()->game()->first();
	}

    public function events()
    {
        return $this->morphToMany(Event::class, 'listenerable', 'event_listeners');
    }

	public function findGameObject(string $name): GameObject | null
	{
		return $this->game()->findGameObject($name);
	}

	public function parentGameObject(): GameObject | null
	{
		$parentGameObject= $this->gameObject()->first()->parent()->first();
		$parentIsRoot = $parentGameObject->isRoot();
		return $parentIsRoot ? null : $parentGameObject;
	}

	public function super(): BelongsTo
	{
		return $this->belongsTo(Component::class, 'id');
	}

	public function subclass(): Component
	{
		if (ReflectionUtils::isSubclassOf($this->type, PersistentComponent::class)) {
			return $this->type::find($this->id);
		}
		return new $this->type($this->attributes);
	}

	public function view()
	{
		return null;
	}

	protected static function createFromSlug(
		Base $gameObject,
		string $slug_type,
		array $attributes
	): Component {
		$type = ReflectionUtils::componentClassFromSlug($slug_type);
		$component = $gameObject->components()->create(['type' => $type, 'enabled' => $attributes['enabled'] ?? true]);
		unset ($attributes['enabled'], $attributes['type']);

		if (ReflectionUtils::isSubclassOf($type, PersistentComponent::class)) {
			return $type::create(array_merge(['id' => $component->id], $attributes));
		}

		return new $type(array_merge(['id' => $component->id], $attributes));
		// return $type::create(array_merge(['id' => $component->id], $attributes));
	}

	public function __tostring(): string
	{
		return class_basename($this);
	}
}
