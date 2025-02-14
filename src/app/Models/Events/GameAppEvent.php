<?php

namespace App\Models\Events;

use App\Models\GameApp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameAppEvent extends Model
{
	protected $fillable = ['name', 'description', 'game_app_id'];

	public $timestamps = false;

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = Str::slug($value, '_');
	}

	public function gameApp(): BelongsTo
	{
		return $this->belongsTo(GameApp::class);
	}

	public function gameEventListenerManagers()
	{
		return $this->hasMany(GameEventListenerManager::class);
	}

	/**
	 * Recupera un GameAppEvent según el game_app_id y el nombre, utilizando cache permanente.
	 *
	 * @param  int    $gameAppId
	 * @param  string $eventName
	 * @return GameAppEvent|null
	 */
	public static function findGameAppEventByName(int $gameAppId, string $eventName): GameAppEvent|null
	{
		$cacheKey = "game_app_event_{$gameAppId}_{$eventName}";

		return Cache::rememberForever($cacheKey, function () use ($gameAppId, $eventName) {
			return self::where([
				'game_app_id' => $gameAppId,
				'name' => $eventName,
			])->first();
		});
	}
}
