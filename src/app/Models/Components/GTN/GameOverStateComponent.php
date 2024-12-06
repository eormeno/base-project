<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class GameOverStateComponent extends StateViewComponent
{
    protected $table = 'gtn_game_over_state_components';
    protected $view_name = 'guess-the-number.game-over';

    public static function state(): string | null
    {
        return 'game-over';
    }

    public function messages(): array
    {
        return [];
    }
}
