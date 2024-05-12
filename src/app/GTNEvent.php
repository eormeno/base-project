<?php

namespace App;

enum GTNEvent: int
{
    case none = 0;
    case guess = 1;
    case want_to_play = 2;
    case play_again = 3;
}
