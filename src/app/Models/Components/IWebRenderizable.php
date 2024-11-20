<?php

namespace App\Models\Components;

interface IWebRenderizable
{
    public function state(): string;
    public function view(): string;
    public function slot(): string;
}
