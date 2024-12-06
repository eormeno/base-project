<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;


class ShowingClueStateComponent extends StateViewComponent
{
    protected $table = 'gtn_showing_clue_state_components';
    protected $view_name = 'guess-the-number.showing-clue';

    public static function state(): string | null
    {
        return 'showing-clue';
    }

    public function onStart(): void
    {
        $gtnService = $this->getService('gtn-service')->toArray();
        $this->messages = app(MessageService::class)->getMessages(self::class, $gtnService);
    }

    public function onWantToPlayEvent()
    {
        return PlayingStateComponent::state();
    }

    public function onAnotherChallengeEvent()
    {
        return PreparingStateComponent::state();
    }
}
