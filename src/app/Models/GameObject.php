<?php

namespace App\Models;

use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Utils\ReflectionUtils;
use App\Models\Components\IState;
use App\Helpers\InstantiateHelper;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;
use App\Models\Components\WebRendererComponent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameObject extends Model
{
    use HasFactory, DebugHelper;

    public $timestamps = false;

    protected $fillable = ['name', 'active', 'state', 'game_object_id'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function handle(FrontEvent $event)
    {
        if (!$this->active) {
            return;
        }
        $this->log("GameObject ($this->name) handling event '{$event->event['event']}'");
        // itera todos los componentes del GameObject
        $components = $this->components()->get();
        foreach ($components as $component) {
            if ($component->enabled == false) {
                continue;
            }
            $subclass = $component->subclass();
            if (is_subclass_of($subclass, IState::class)) {
                $subclass->handle($event);
            }
        }
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

    public function componentsIterator(callable $callback)
    {
        $components = $this->components()->get();
        foreach ($components as $component) {
            $subclass = $component->subclass();
            $callback($component, $subclass);
        }
    }

    /**
     * Agregar un componente al GameObject.
     *
     * @param string $slug_type Nombre slug del componente a agregar. Por ejemplo: 'gtn.game-data'
     * @param array $attributes Atributos específicos del componente
     * @return mixed El modelo del componente específico
     */
    public function addComponent(string $slug_type, array $attributes = []) : Component
    {
        return InstantiateHelper::createComponent($this, $slug_type, $attributes);
    }

    /**
     * Obtener un componente del GameObject.
     *
     * @param string $slug_type Clase del componente a obtener (slug)
     * @return mixed|null El componente específico o null si no existe
     */
    public function getComponent(string $slug_type) : ?Component
    {
        $type = ReflectionUtils::componentClass($slug_type);
        $component = $this->components()->where('type', $type)->first();
        return $component ? $type::find($component->id) : null;
    }

    /**
     * Eliminar un componente del GameObject.
     *
     * @param string $slug_type Clase del componente a eliminar (slug)
     * @return bool Indica si se eliminó correctamente
     */
    public function removeComponent(string $slug_type): bool
    {
        $type = ReflectionUtils::componentClass($slug_type);
        $component = $this->components()->where('type', $type)->first();
        if ($component) {
            return $component->delete(); // Esto elimina tanto el componente base como el específico por la relación
        }
        return false;
    }

    public function view()
    {
        $components = $this->components()->get();
        foreach ($components as $component) {
            if (!$component->enabled) {
                continue;
            }
            $subclass = $component->subclass();
            if (is_subclass_of($subclass, WebRendererComponent::class)) {
                $subclassShortName = ReflectionUtils::short($subclass);
                $this->log("Rendering component {$subclassShortName} {$this->state}");
                //return $subclass->view();
            }
        }
        $html = "<h4>View not found</h4>";
        return $html;
    }
}
