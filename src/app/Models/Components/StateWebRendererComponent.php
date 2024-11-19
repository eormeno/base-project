<?php

namespace App\Models\Components;

use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateWebRendererComponent extends Component
{
    protected $fillable = ['id', 'rendered_state', 'slot', 'provided_slots', 'view'];
    protected $casts = [
        'provided_slots' => 'array',
    ];

    public function super() : BelongsTo
    {
        return $this->belongsTo(Component::class, 'id', 'id');
    }

    public function onStart(): void
    {
    }
}
