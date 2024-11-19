<?php

namespace App\Models\Components;

interface WebRenderizable
{
    public function view(): string;
    public function slot(): string;
}
