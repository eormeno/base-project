<?php

namespace App\Models\Components;

use App\Utils\ReflectionUtils;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateViewComponent extends Component implements IState, IView
{
    protected $fillable = ['id'];
    protected $view_name = 'web-renderer.default';
    protected $state = null;

    public function super(): BelongsTo
    {
        return $this->belongsTo(Component::class, 'id');
    }

    public function getEnabledAttribute()
    {
        return $this->super->enabled;
    }

    public function setEnabledAttribute($value)
    {
        $this->super->update(['enabled' => $value]);
    }

    public function handle(array $event): string
    {
        $className = ReflectionUtils::short($this);
        $eventName = $event['event'];
        $this->log("Event '$eventName' is beign handled by '$className'.");

        return $this->state;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function onEnter(): void
    {
    }

    public function onExit(): void
    {
    }

    public function view()
    {
        $view = view($this->view_name, $this->publicPropertiesToArray());
        return $view;
    }

    private function publicPropertiesToArray(): array
    {
        $exclude = ['context', 'arrStrChildrenVID'];
        $properties = get_object_vars($this);
        $array = [];
        foreach ($properties as $key => $value) {
            if (in_array($key, $exclude)) {
                continue;
            }
            // exclude private and protected properties
            if (strpos($key, "\0") === false) {
                $array[$key] = $value;
            }
        }
        return $array;
    }
}
