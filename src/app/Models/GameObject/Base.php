<?php

namespace App\Models\GameObject;

use App\Models\Game;
use App\Utils\ReflectionUtils;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Base extends Model
{
	protected const INITIAL_STATE = 'initial';

	public $timestamps = false;

	protected $fillable = ['name', 'active', 'state_component_id', 'game_object_id', 'game_id', 'state', 'state_components', 'indexed_children'];

	protected $casts = [
		'active' => 'boolean',
		'state_components' => 'array',
		'indexed_children' => 'array',
	];

	public function game(): BelongsTo
	{
		return $this->belongsTo(Game::class);
	}

	public function getCurrentStateAttribute(): HasOne
	{
		return $this->hasOne(Component::class, 'id', 'state_component_id');
	}

	public function setCurrentStateAttribute(Component $state): void
	{
		$this->update(['state_component_id' => $state->id]);
	}

	public function components(): HasMany
	{
		return $this->hasMany(Component::class);
	}

	public function children(): HasMany
	{
		return $this->hasMany(GameObject::class);
	}

	public function parent(): BelongsTo
	{
		return $this->belongsTo(GameObject::class);
	}

	protected function currentStateComponent(): ?Component
	{
		$state_components = $this->state_components ?? [];
		$current_state = $this->state;
		if($current_state_component_id = $state_components[$current_state] ?? null) {
			return Component::find($current_state_component_id)->subclass();
		}
		return null;
	}

	public function componentsIterator(
		callable $callback,
		bool $enabled = true
	): void {
		$components = $this->components()->get();
		foreach ($components as $component) {
			if ($component->enabled !== $enabled) {
				continue;
			}
			$subclass = $component->subclass();
			$callback($subclass);
		}
	}

	public function addComponent(string $slug_type, array $attributes = []): Component
	{
		return Component::createFromSlug($this, $slug_type, $attributes);
	}

	public function getComponent(string $slug_type): ?Component
	{
		$type = ReflectionUtils::componentClass($slug_type);
		$component = $this->components()->where('type', $type)->first();
		return $component ? $type::find($component->id) : null;
	}

	public function removeComponent(string $slug_type): bool
	{
		$type = ReflectionUtils::componentClass($slug_type);
		$component = $this->components()->where('type', $type)->first();
		if ($component) {
			return $component->delete(); // Esto elimina tanto el componente base como el específico por la relación
		}
		return false;
	}
}
