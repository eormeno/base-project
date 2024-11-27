<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class PlayingStateComponent extends StateViewComponent
{
    protected $table = 'gtn_playing_state_components';
    protected $view_name = 'guess-the-number.playing';
    protected $state = 'playing';
}
