<?php

namespace App\Models\Events;

use App\Models\Game;
use App\Models\GameService;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
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

	public function components()
	{
		return $this->morphedByMany(Component::class, 'listenerable', 'event_listeners');
	}

	public function gameServices()
	{
		return $this->morphedByMany(GameService::class, 'listenerable', 'event_listeners');
	}


}
