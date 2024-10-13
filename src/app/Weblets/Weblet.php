<?php

namespace App\Weblets;

use App\Weblets\Collections\ComponentCollection;

class Weblet {

    private string $id;
    private string $name;
    private string $title;
    private Component $root;
    private ComponentCollection $components;

}
