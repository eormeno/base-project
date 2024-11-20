<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebRendererComponent;

class AskingToPlayComponent extends WebRendererComponent
{
    protected $view_name = 'guess-the-number.asking-to-play';

    public string $description = "Esta es la descripción.";
    public string $yes_i_accept_the_challenge = "Si acepto";
    public array $ranking = [];

    public function onWantToPlayEvent()
    {
    }

}
