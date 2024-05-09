<?php

namespace App;

enum MessageType: int
{
    case INFO = 0;
    case SUCCESS = 1;
    case ERROR = 2;
}
