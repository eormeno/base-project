<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;


class ShowingClueStateComponent extends StateViewComponent
{
    protected $table = 'gtn_showing_clue_state_components';
    protected $view_name = 'guess-the-number.showing-clue';

    protected $messages = [];

    public static function state(): string | null
    {
        return 'showing-clue';
    }

    // public function onWantToPlayEvent()
    // {
    //     return PlayingStateComponent::state();
    // }

    public function onAnotherChallengeEvent()
    {
        return PreparingStateComponent::state();
    }

    public function messages(): array
    {
        $clues = $this->getService('clue-service')->getClues();
        $messages = app(MessageService::class)->getMessages(self::class, $clues);
        return $messages;
    }
}
