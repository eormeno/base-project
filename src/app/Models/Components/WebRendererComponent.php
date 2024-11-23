<?php

namespace App\Models\Components;

use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebRendererComponent extends Component
{
    protected $fillable = ['id'];

    protected $view_name = 'web-renderer.default';

    public function super(): BelongsTo
    {
        return $this->belongsTo(Component::class, 'id');
    }

    public function getActiveAttribute()
    {
        return $this->super->active;
    }

    public function setActiveAttribute($value)
    {
        $this->super->update(['active' => $value]);
    }

    public function view()
    {
        $view = view($this->view_name, $this->publicPropertiesToArray());
        return $view;
    }

    private function publicPropertiesToArray(): array
    {
        $exclude = ['context', 'arrStrChildrenVID'];
        $properties = get_object_vars($this);
        $array = [];
        foreach ($properties as $key => $value) {
            if (in_array($key, $exclude)) {
                continue;
            }
            // exclude private and protected properties
            if (strpos($key, "\0") === false) {
                $array[$key] = $value;
            }
        }
        return $array;
    }
}
