<?php

namespace App\Models;

use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory, DebugHelper;
    protected $fillable = ['invitation_code', 'game_app_id', 'game_object_id'];

    public function handle(FrontEvent $event)
    {
        $this->log("Game $this->invitation_code handling event '{$event->event['event']}'");
    }

    public function gameApp(): BelongsTo
    {
        return $this->belongsTo(GameApp::class);
    }

    public function gameObject(): HasOne
    {
        return $this->hasOne(GameObject::class, 'id', 'game_object_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function services(): HasMany
    {
        return $this->hasMany(GameService::class);
    }

    public function addService(string $slug, GameService $service): void
    {
        $this->services()->create([
            'game_id' => $this->id,
            'type' => (new \ReflectionClass($service))->getName(),
            'slug' => $slug,
        ]);
    }

    public function getService(string $slug): GameService
    {
        $service = $this->services->firstWhere('slug', $slug);
        return new $service->type();
    }
}
