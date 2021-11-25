<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Tank;

enum Location: int
{
    case BRIDGE     = 0;
    case RIVER_BANK = 1;

    public function toString(): string
    {
        return match ($this) {
            self::BRIDGE     => 'bridge',
            self::RIVER_BANK => 'river bank',
        };
    }
}
