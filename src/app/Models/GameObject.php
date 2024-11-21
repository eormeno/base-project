<?php

namespace App\Models;

use App\Models\Component;
use Illuminate\Support\Str;
use App\Utils\ReflectionUtils;
use Illuminate\Database\Eloquent\Model;
use App\Models\Components\WebRendererComponent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameObject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'active', 'state', 'game_object_id'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Mutator for the 'name' attribute that slugifies it.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = Str::slug($value);
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

    /**
     * Agregar un componente al GameObject.
     *
     * @param string $slug_type Nombre slug del componente a agregar. Por ejemplo: 'gtn.game-data'
     * @param array $attributes Atributos específicos del componente
     * @return mixed El modelo del componente específico
     */
    public function addComponent(string $slug_type, array $attributes = [])
    {
        $type = ReflectionUtils::componentClass($slug_type);
        // Crear el componente base
        $component = $this->components()->create([
            'type' => $type,
            'active' => $attributes['active'] ?? true,
        ]);

        // Crear el componente específico asociado
        return $type::create(array_merge(['id' => $component->id], $attributes));
    }

    /**
     * Obtener un componente específico del GameObject.
     *
     * @param string $type Clase del componente a obtener
     * @return mixed|null El componente específico o null si no existe
     */
    public function getComponent(string $type)
    {
        $component = $this->components()->where('type', $type)->first();
        return $component ? $type::find($component->id) : null;
    }

    /**
     * Eliminar un componente específico del GameObject.
     *
     * @param string $type Clase del componente a eliminar
     * @return bool Indica si se eliminó correctamente
     */
    public function removeComponent(string $type)
    {
        $component = $this->components()->where('type', $type)->first();
        if ($component) {
            return $component->delete(); // Esto elimina tanto el componente base como el específico por la relación
        }
        return false;
    }

    public function view() {
        $components = $this->components()->get();
        foreach ($components as $component) {
            if ($component->active == false) {
                continue;
            }
            $subclass = $component->subclass();
            if (is_subclass_of($subclass, WebRendererComponent::class)) {
                return $subclass->view();
            }
        }
        $html = "<h4>View not found</h4>";
        return $html;
    }
}
