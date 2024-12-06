<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class InitialStateViewComponent extends StateViewComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.initial';

    protected $fillable=[
        'messages',
    ];
    protected $casts = [
        'messages' => 'array',
    ];

    public static function state(): string | null
    {
        return 'initial';
    }

    public function onStart(): void
    {
        //$gameDataComponent = $this->findComponent('gtn.game-data')->toArray();
        $gtnService = $this->getService('gtn-service')->toArray();
        $this->messages = app(MessageService::class)->getMessages(self::class, $gtnService);
        $this->save();
    }

    public function onWantToPlayEvent()
    {
        return PreparingStateComponent::state();
    }
}
