<?php

namespace App\Enums;

enum Status: string
{
    case OPEN = "open";
    case WIN = "win";
    case LOSE = "lose";
}
