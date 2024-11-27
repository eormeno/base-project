<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\WebStateRendererComponent;

class InitialStateViewComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.asking-to-play';
    protected $state = 'initial';

    protected $fillable=[
        'messages',
    ];
    protected $casts = [
        'messages' => 'array',
    ];

    public string $description = "";
    public string $yes_i_accept_the_challenge = "";
    public array $ranking = [];

    public function onAwake(): void
    {
        $this->messages = app(MessageService::class)->getMessages(self::class);
        $this->save();
    }

    public function onWantToPlayEvent()
    {
    }
}
