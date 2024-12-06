<?php

namespace App\Models\GameObject;

use App\Models\Components\IView;

class GameObjectViewProvider extends GameObjectFrontEventListener implements IView
{
    // TODO Cambiar esto urgente!
    public function messages(): array {
        return [];
    }

    public function view()
    {
        $currentStateViewComponent = $this->currentStateComponent();
        if ($currentStateViewComponent === null) {
            return "<h4>State View not found</h4>";
        }
        return $currentStateViewComponent->view();
    }
}
