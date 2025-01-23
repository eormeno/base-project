<?php

namespace App\Models\Components;

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
	protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke', 'state', 'messages'];
	protected $casts = [
		'enabled' => 'boolean',
		'awoke' => 'boolean',
		'messages' => 'array',
	];

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

	public function view()
	{
		return null;
	}

	protected static function createFromSlug(
		Base $gameObject,
		string $slug_type,
		array $attributes
	): Component {
		$type = ReflectionUtils::componentClass($slug_type);
		$component = $gameObject->components()->create(['type' => $type, 'enabled' => $attributes['enabled'] ?? true]);
		unset ($attributes['enabled'], $attributes['type']);
		return $type::create(array_merge(['id' => $component->id], $attributes));
	}
}
