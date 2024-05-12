<?php

namespace App;

enum GTNState: int
{
    case INITIAL = 0;
    case PREPARING = 1;
    case ASKING_PLAY = 2;
    case PLAYING = 3;
    case SUCCESS = 4;
    case GAME_OVER = 5;
    case ASKING_PLAY_AGAIN = 6;
}
