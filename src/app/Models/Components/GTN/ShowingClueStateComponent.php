<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;


class ShowingClueStateComponent extends StateViewComponent
{
    protected $table = 'gtn_showing_clue_state_components';
    protected $view_name = 'guess-the-number.showing-clue';
    protected $state = 'showing-clue';
}
