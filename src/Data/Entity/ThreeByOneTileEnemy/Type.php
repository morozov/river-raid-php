<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\ThreeByOneTileEnemy;

enum Type: int
{
    case HELICOPTER_REGULAR  = 1;
    case SHIP                = 2;
    case HELICOPTER_ADVANCED = 3;
    case TANK                = 4;
    case FIGHTER             = 5;

    public function toString(): string
    {
        return match ($this) {
            self::HELICOPTER_REGULAR  => 'regular helicopter',
            self::SHIP                => 'ship',
            self::HELICOPTER_ADVANCED => 'advanced helicopter',
            self::TANK                => 'tank',
            self::FIGHTER             => 'fighter',
        };
    }
}
