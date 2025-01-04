<?php

namespace App\Models\GameObject;

use App\Models\Components\IView;

abstract class ViewStateContextBase extends StateContextBase implements IView
{
    public function view()
    {
        $currentStateViewComponent = $this->currentStateComponent();
        if ($currentStateViewComponent === null) {
            return "<h4>State View not found</h4>";
        }
        return $currentStateViewComponent->view();
    }
}
