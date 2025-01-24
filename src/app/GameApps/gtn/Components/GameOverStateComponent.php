<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\PersistentComponent;
use App\Models\Components\StateViewComponent;

class GameOverStateComponent extends PersistentComponent
{
    //protected $table = 'gtn_game_over_state_components';
    protected $view_name = 'guess-the-number.game-over';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

    public function onStart(): void
    {
		parent::onStart();
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
		return 'preparing';
    }

    public function onExitEvent()
    {
		return 'initial';
    }

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'default';
		}
		return base64_encode(view($this->view_name, $data));
	}
}
