<?php

namespace App\Models\GameObject;

abstract class ViewStateContextBase extends StateContextBase
{
    public function view()
    {
		$mergedViews = [];
		$this->componentsIterator(function ($component) use (&$mergedViews) {
			// echos the class name of the component
			// echo get_class($component) . "\n";
			$view = $component->view();
			if ($view !== null) {
				if (is_array($view)) {
					$mergedViews = array_merge($mergedViews, $view);
				} else {
					$mergedViews[] = $view;
				}
			}
		});
		return $mergedViews ?? null;
    }

	public function view2()
    {
        $currentStateViewComponent = $this->currentStateComponent();
        if ($currentStateViewComponent === null) {
            return "<h4>State View not found</h4>";
        }
        return $currentStateViewComponent->view();
    }

}
