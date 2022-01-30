<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

enum Type: int
{
    case HELICOPTER_REGULAR  = 1;
    case SHIP                = 2;
    case HELICOPTER_ADVANCED = 3;
    case TANK                = 4;
    case FIGHTER             = 5;
    case BALLOON             = 6;
    case FUEL_STATION        = 7;
}
