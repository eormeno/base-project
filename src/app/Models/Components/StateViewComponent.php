<?php

namespace App\Models\Components;

use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class StateViewComponent extends Component implements IState, IView
{
    protected $fillable = ['id'];
    protected $view_name = 'web-renderer.default';

    public static function state(): string | null
    {
        return null;
    }

    public function getEnabledAttribute()
    {
        return $this->super->enabled;
    }

    public function setEnabledAttribute($value)
    {
        $this->super->update(['enabled' => $value]);
    }

    public function handleStateEvent(array $event): string | null
    {
        $eventName = $event['event'];
        $eventData = $event['data'];
        $source = $event['source'];
        $destination = $event['destination'];

        if ($eventName === null || $eventName === '' || $eventName === 'reload') {
            return $this->passTo();
        }

        $method = 'on' . CaseConverters::snakeToPascal($eventName) . 'Event';
        if (method_exists($this, $method)) {
            $ref_cls = ReflectionUtils::invokeMethod($this, $method, $eventData);
            if ($ref_cls) {
                return $ref_cls;
            }
        }

        return $this->passTo();
    }

    public function onEnter(): void
    {
    }

    public function onExit(): void
    {
    }

    public function passTo(): string
    {
        return get_class($this)::state();
    }

    public function view()
    {
        $view = view($this->view_name, $this->modelAttributesToArray());
        return $view;
    }

    private function modelAttributesToArray(): array
    {
        $attributes = $this->getAttributes();
        $array = [];
        foreach ($attributes as $key => $value) {
            $array[$key] = json_decode($value);
        }
        return $array;
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
