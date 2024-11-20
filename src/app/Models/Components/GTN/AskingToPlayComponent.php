<?php

namespace App\Models\Components\GTN;

use App\Models\Component;
use App\Models\Components\IWebRenderizable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AskingToPlayComponent extends Component implements IWebRenderizable
{
    protected $fillable = ['id'];
    protected const STATE = 'asking-to-play';
    protected const VIEW = 'guess-the-number.asking-to-play';
    protected const SLOT = 'root';

    public string $description = "Esta es la descripción.";
    public string $yes_i_accept_the_challenge = "Si acepto";
    public array $ranking = [];

    public function super() : BelongsTo
    {
        return $this->belongsTo(Component::class, 'id', 'id');
    }

    public function state(): string
    {
        return self::STATE;
    }

    public function view(): string
    {
        return self::VIEW;
    }

    public function slot(): string
    {
        return self::SLOT;
    }

    public function onWantToPlayEvent()
    {
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
