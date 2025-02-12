<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $fillable = ['name', 'description'];

	/**
     * Mutator para convertir el valor del nombre en un slug.
     *
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::slug($value, '_');
    }

	public function components()
	{
		return $this->morphedByMany(Component::class, 'listenerable', 'event_listeners');
	}

	public function gameServices()
	{
		return $this->morphedByMany(GameService::class, 'listenerable', 'event_listeners');
	}

	public function listeners()
	{
		return $this->gameObjects->merge($this->gameServices);
	}
}
