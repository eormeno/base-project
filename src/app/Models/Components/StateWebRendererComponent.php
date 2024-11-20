<?php

namespace App\Models\Components;

use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateWebRendererComponent extends Component implements IWebRenderizable
{
    protected $fillable = ['id', 'rendered_state', 'slot', 'view'];

    public function super() : BelongsTo
    {
        return $this->belongsTo(Component::class, 'id', 'id');
    }

    public function state(): string
    {
        return $this->rendered_state;
    }

    public function view(): string
    {
        return $this->view;
    }

    public function slot(): string
    {
        return $this->slot;
    }

    public function onStart(): void
    {
    }
}
