<?php

namespace App;

enum GTNEvent: int
{
    case NONE = 0;
    case GUESS = 1;
    case PLAY_AGAIN = 2;
}
