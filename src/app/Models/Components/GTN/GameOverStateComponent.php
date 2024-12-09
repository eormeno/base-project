<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class GameOverStateComponent extends StateViewComponent
{
    protected $table = 'gtn_game_over_state_components';
    protected $view_name = 'guess-the-number.game-over';

    public static function state(): string|null
    {
        return 'game-over';
    }

    public function onStart(): void
    {
        $gtn_service = $this->getService('gtn-service');
        $params = [
            'user_name' => $gtn_service->user->name,
            'random_number' => $gtn_service->random_number,
        ];
        $this->updateView([
            'i18n' => [
                'notification' => $params,
                'subtitle' => $params,
                'play_again' => null,
                'exit' => null,
            ]
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
