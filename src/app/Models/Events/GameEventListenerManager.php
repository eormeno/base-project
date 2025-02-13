<?php

namespace App\Models\Events;

use App\Models\Game;
use App\Events\GameEvent;
use App\Models\GameService;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;

class GameEventListenerManager extends Model
{
	protected $fillable = ['game_app_event_id'];

	public $timestamps = false;

	public static function addListener(Game $game, array|string $eventNames, $listener)
	{
		$gameAppId = $game->gameApp()->first()->id;
		$eventNames = is_array($eventNames) ? $eventNames : [$eventNames];
		foreach ($eventNames as $name) {
			$gameAppEvent = GameAppEvent::firstOrCreate(['game_app_id' => $gameAppId, 'name' => $name]);
			$event = static::firstOrCreate(['game_app_event_id' => $gameAppEvent->id]);
			$event->components()->attach($listener);
		}
	}

	public static function componentListenersOf(GameEvent $gameEvent)
	{
		$eventName = $gameEvent->event['event'];
		$gameAppId = $gameEvent->game->gameApp()->first()->id;
		$gameAppEvent = GameAppEvent::where(['game_app_id' => $gameAppId, 'name' => $eventName])->first();
		if (!$gameAppEvent) {
			return [];
		}
		$event = static::where(['game_app_event_id' => $gameAppEvent->id])->first();
		return $event->components;
	}


	public function components()
	{
		return $this->morphedByMany(Component::class, 'listenerable', 'event_listeners');
	}

	public function gameServices()
	{
		return $this->morphedByMany(GameService::class, 'listenerable', 'event_listeners');
	}


}
