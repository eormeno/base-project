<?php

namespace App\Models\Components;

use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateViewComponent extends Component implements IState, IView
{
    protected $fillable = ['id'];
    protected $view_name = 'web-renderer.default';

    public static function state(): string|null
    {
        return null;
    }

    public function handleStateEvent(array $event): string|null
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
        $data = $this->messages();
        if (!isset($this->view_name)) {
            $this->view_name = 'web-renderer.default';
        }
        $view = view($this->view_name, $data);
        return $view;
    }
}
