<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class ShowingClueStateComponent extends StateViewComponent
{
    protected $table = 'gtn_showing_clue_state_components';
    protected $view_name = 'guess-the-number.showing-clue';

    public static function state(): string|null
    {
        return 'showing-clue';
    }

    public function onStart(): void
    {
        $clues = $this->getService('clue-service')->getClues();
        $messages = [
            'i18n' => [
                'title' => null,
                'good_luck' => null,
                'yes_button' => null,
                'another_challenge' => null,
                'clues' => $clues
            ]
        ];
        $this->updateView($messages);
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
