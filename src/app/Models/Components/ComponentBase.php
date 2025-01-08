<?php

namespace App\Models\Components;

use ReflectionClass;
use App\Utils\ReflectionUtils;
use App\Models\GameObject\Base;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentBase extends Model
{
	public $timestamps = false;
	protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke', 'state', 'messages'];
	protected $casts = [
		'enabled' => 'boolean',
		'awoke' => 'boolean',
		'messages' => 'array',
	];

	/**
	 * The state the game object is in when this component is enabled. A null response means the enabling state is
	 * controlled by the enable attribute.
	 *
	 * @return string|null
	 */
	protected static function enablingState(): string|null
	{
		return null;
	}

	/**
	 * Whether the component is enabled. If enablingState() returns null, this attribute is used to determine if the
	 * component is enabled.
	 *
	 * @return bool
	 */
	public function getEnabledAttribute(): bool
	{
		if (self::enablingState() === null) {
			return $this->super()->enabled;
		}
		return $this->gameObject()->state === self::enablingState();
	}

	/**
	 * Set the enabled attribute.
	 *
	 * @param bool $value
	 * @return void
	 */
	public function setEnabledAttribute(bool $value): void
	{
		$this->super()->update(['enabled' => $value]);
	}

	public function gameObject(): BelongsTo
	{
		return $this->belongsTo(GameObject::class);
	}

	public function super(): BelongsTo
	{
		return $this->belongsTo(Component::class, 'id');
	}

	public function subclass(): Component
	{
		return $this->type::find($this->id);
	}

	protected static function createFromSlug(
		Base $gameObject,
		string $slug_type,
		array $attributes
	): Component {
		$type = ReflectionUtils::componentClass($slug_type);
		$component = $gameObject->components()->create(self::stateComponentConfig($type));
		return $type::create(array_merge(['id' => $component->id], $attributes));
	}

	private static function stateComponentConfig($type): array
	{
		// TODO Try to remove this method
		$attributes = ['type' => $type, 'enabled' => true];
		$class = new ReflectionClass($type);
		if ($class->implementsInterface(IState::class)) {
			return array_merge($attributes, ['enabled' => false]);
		}
		return $attributes;
	}
}
