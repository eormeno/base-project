<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class InitialStateViewComponent extends StateViewComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.initial';
    protected $state = 'initial';

    protected $fillable=[
        'messages',
    ];
    protected $casts = [
        'messages' => 'array',
    ];

    public function onAwake(): void
    {
        $this->messages = app(MessageService::class)->getMessages(self::class);
        $this->save();
    }

    public function onWantToPlayEvent()
    {
    }
}
