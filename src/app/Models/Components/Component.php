<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Component extends Model
{
    protected $fillable = ['active', 'properties'];
    protected $casts = [
        'properties' => 'array',
        'active' => 'boolean'
    ];

    public function componentable(): MorphTo
    {
        return $this->morphTo();
    }
}
