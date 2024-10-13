<?php

namespace App\Weblets;

use App\Weblets\Collections\StateCollection;
use App\Weblets\Collections\AttributeCollection;

class Component {
    private string $name;
    private AttributeCollection $attributes;
    private StateCollection $states;
}
