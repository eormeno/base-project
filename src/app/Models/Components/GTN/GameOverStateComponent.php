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

    public function onEnter(): void
    {
        $gtn_service = $this->getService('gtn_service');
        $this->updateView([
            'notification_txt' => $gtn_service->user->name,
            'subtitle_txt' => $gtn_service->random_number,
            'play_again_txt' => null,
            'exit_txt' => null,
        ]);
    }

    public function onPlayAgainEvent()
    {
        return PreparingStateComponent::state();
    }

    public function onExitEvent()
    {
        return InitialStateViewComponent::state();
    }
}
