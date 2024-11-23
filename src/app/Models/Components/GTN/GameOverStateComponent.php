<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class GameOverStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_game_over_state_components';
    protected $view_name = 'guess-the-number.game-over';
    protected $state = 'game-over';
}
