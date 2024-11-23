<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class PlayingStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_playing_state_components';
    protected $view_name = 'guess-the-number.playing';
    protected $state = 'playing';
}
