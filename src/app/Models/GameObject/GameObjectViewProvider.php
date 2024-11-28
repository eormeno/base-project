<?php

namespace App\Models\GameObject;

use App\Utils\ReflectionUtils;
use App\Models\Components\IView;

class GameObjectViewProvider extends GameObjectFrontEventListener implements IView
{
    public function view()
    {
        $components = $this->components()->get();
        foreach ($components as $component) {
            if (!$component->enabled) {
                continue;
            }
            $subclass = $component->subclass();
            if (is_subclass_of($subclass, IView::class)) {
                $subclassShortName = ReflectionUtils::short($subclass);
                $this->log("Rendering component {$subclassShortName} {$this->state}");
                return $subclass->view();
            }
        }
        $html = "<h4>View not found</h4>";
        return $html;
    }
}
