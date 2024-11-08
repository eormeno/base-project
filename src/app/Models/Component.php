<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Component extends Model
{
    protected $fillable = ['active_on_state'];

    public function componentable(): MorphTo
    {
        return $this->morphTo();
    }
}
