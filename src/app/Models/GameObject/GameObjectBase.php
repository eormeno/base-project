<?php

namespace App\Models\GameObject;

use App\Traits\DebugHelper;
use App\Utils\ReflectionUtils;
use App\Helpers\InstantiateHelper;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameObjectBase extends Model
{
    use HasFactory, DebugHelper;

    protected const INITIAL_STATE = 'initial';

    public $timestamps = false;

    protected $fillable = ['name', 'active', 'state_component_id', 'game_object_id'];

    protected $casts = [
        'active' => 'boolean',
    ];

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
        return $this->hasMany(GameObjectBase::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(GameObjectBase::class);
    }

    protected function findComponentForState(string $state): ?Component
    {
        $stateComponent = $this->components()->where('state', $state)->first();
        if (!$stateComponent) {
            return null;
        }
        return $stateComponent->subclass();
    }

    protected function currentStateComponent(): ?Component
    {
        $current = $this->current_state->first();
        if ($current !== null) {
            return $current->subclass();
        }
        $initialStateComponent = $this->findComponentForState(self::INITIAL_STATE);
        if ($initialStateComponent === null) {
            return null;
        }
        $initialStateComponent->enabled = true;
        $initialStateComponent->onEnter();
        $this->current_state = $initialStateComponent;
        return $initialStateComponent;
    }

    public function componentsIterator(
        callable $callback,
        ?string $type = null,
        ?bool $enabled = null
    ): void {
        $components = $this->components()->get();
        foreach ($components as $component) {
            if ($enabled !== null && $component->enabled !== $enabled) {
                continue;
            }
            $subclass = $component->subclass();
            if ($type !== null && !is_subclass_of($subclass, $type)) {
                continue;
            }
            $callback($component, $subclass);
        }
    }

    public function addComponent(string $slug_type, array $attributes = []): Component
    {
        return InstantiateHelper::createComponent($this, $slug_type, $attributes);
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
