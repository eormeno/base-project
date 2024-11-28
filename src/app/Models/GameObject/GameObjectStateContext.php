<?php

namespace App\Models\GameObject;

use App\Utils\ReflectionUtils;
use App\Contracts\IStateContext;

class GameObjectStateContext extends GameObjectFrontEventListener implements IStateContext
{
    public function request(array $event): string
    {
        $components = $this->components()->get();
        foreach ($components as $component) {
            if (!$component->enabled) {
                continue;
            }
            $subclass = $component->subclass();
            if (is_subclass_of($subclass, IStateContext::class)) {
                $subclassShortName = ReflectionUtils::short($subclass);
                $this->log("Handling event {$event['event']} with component {$subclassShortName}");
                return $subclass->request($event);
            }
        }
        $html = "<h4>State not found</h4>";
        return $html;
    }
}
