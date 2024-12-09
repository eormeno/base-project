<?php

namespace App\Models\Components;

use App\Models\GameService;
use App\Traits\DebugHelper;
use Illuminate\Support\Str;
use App\Utils\ReflectionUtils;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
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

    public function updateView(array $attributes): bool
    {
        $view_name = $this->view_name ?? '';
        $messages = $this->super->messages ?? [];
        // si está la clave i18n, entonces se debe traducir
        if (array_key_exists('i18n', $attributes)) {
            $result = $this->recurseAttrs($attributes['i18n'], $view_name);
            if (!$result) {
                return false;
            }
            // copia los valores de i18n a messages
            $messages = array_merge($messages, $attributes['i18n']);
            unset($attributes['i18n']);
        }
        //$this->recurseAttrs($attributes, $view_name);
        $this->super->messages = array_merge($messages, $attributes);
        $this->super->save();
        return true;
    }

    private function recurseAttrs(array &$array, string $prefijo = ''): bool
    {
        foreach ($array as $clave => &$valor) {
            $valor ??= [];
            if (!is_scalar($clave) && $clave !== null) {
                return false;
            }
            if (is_scalar($valor)) {
                $valor = ['value' => $valor];
            }
            // Concatena las claves para formar la ruta actual
            $rutaBase = $prefijo === '' ? (string) $clave : $prefijo;
            $rutaClave = "$rutaBase.$clave";
            if (__($rutaClave) !== $rutaClave) {
                // if i18n key exists, then use it for i18n the value
                $this->success($rutaClave);
                $valor = __($rutaClave, $valor);
                continue;
            }
            $this->warn($rutaClave);
            if (is_array($valor) && count($valor) > 0) {
                if (!$this->recurseAttrs($valor, $rutaBase)) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function __updateView($key, $value = null): array
    {
        $messages = $this->super->messages;
        if ($messages === null) {
            $messages = [];
        }
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        $messages = array_merge($messages, $key);
        // for each value in $key, apply i18n
        foreach ($messages as $k => $v) {
            // if k ends with _array, then v is an array of keys
            if (Str::endsWith($k, '_array')) {
                continue;
            }
            $messages[$k] = __("guess-the-number.$k", $v);
        }
        $this->super->messages = $messages;
        $this->super->save();
        $this->log(json_encode($messages, JSON_PRETTY_PRINT));
        return $messages;
    }

    protected function messages(): array
    {
        return $this->super->messages;
    }

    protected function findComponent(string $slug_type): Component
    {
        $type = ReflectionUtils::componentClass($slug_type);
        $game_object = $this->super->gameObject;
        return $game_object->components()->first([
            'type' => $type,
        ])->first()->subclass();
    }

    public function subclass(): Component
    {
        return $this->type::find($this->id);
    }

    public function getService(string $slug_type): GameService
    {
        return $this->super->gameObject->game->getService($slug_type);
    }

    public function onAwake(): void
    {
    }

    public function onStart(): void
    {
    }

    public function onUpdate(float $delta): void
    {
    }
}
