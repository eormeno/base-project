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
        //$gameConfigService = app(ServiceService::class)->getService('game-config');
        //$this-log('Game Config Service: ' . $gameConfigService->min_number);
        $gameDataComponent = $this->findComponent('gtn.game-data')->toArray();
        $this->messages = app(MessageService::class)->getMessages(self::class, $gameDataComponent);
        $this->save();

        $gameConfigService = $this->getService('game-config-service');
        $this->info($gameConfigService);
    }

    public function onWantToPlayEvent()
    {
        return PreparingStateComponent::state();
    }
}
