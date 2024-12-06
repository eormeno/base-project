<?php

namespace App\Models\Components;

interface IView
{
    public function messages(): array;

    public function view();
}
