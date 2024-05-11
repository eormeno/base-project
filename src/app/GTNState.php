<?php

namespace App;

enum GTNState: int
{
    case INITIAL = 0;
    case PREPARING = 1;
    case PLAYING = 2;
    case SUCCESS = 3;
    case GAME_OVER = 4;
    case ASKING_PLAY_AGAIN = 5;
}
