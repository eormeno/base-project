<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class PreparingStateComponent extends StateViewComponent
{
    protected $table = 'gtn_preparing_state_components';
    protected $view_name = 'guess-the-number.preparing';

    public static function state(): string | null
    {
        return 'preparing';
    }

    public function onStart(): void
    {
        $this->getService('gtn-service')->startGame();
    }

    public function passTo(): string
    {
        return ShowingClueStateComponent::state();
    }

    public function messages(): array
    {
        // TODO Esto no me gusta, esta clase no debería necesitar implementar este método
        return [];
    }
}
