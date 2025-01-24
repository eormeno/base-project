<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;
use App\Models\Components\PersistentComponent;

class ShowingClueStateComponent extends PersistentComponent
{
    //protected $table = 'gtn_showing_clue_state_components';
    protected $view_name = 'guess-the-number.showing-clue';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

    public function onStart(): void
    {
		parent::onStart();
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
		return 'playing';
    }

    public function onAnotherChallengeEvent()
    {
		return 'preparing';
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
